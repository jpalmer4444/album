<?php

namespace Ajax\Service\Sales;

use Ajax\Service\Sales\ItemsFilterTableArrayService;
use Ajax\Service\Sales\ItemsFilterTableArrayServiceInterface;
use Application\Utility\DateUtils;
use DataAccess\FFM\Entity\Customer;
use DataAccess\FFM\Entity\Product;

/**
 * Description of ItemsFilterTableArrayService
 *
 * @author jasonpalmer
 */
class ItemsFilterTableArrayService implements ItemsFilterTableArrayServiceInterface {

    protected $logger;
    protected $pricingconfig;
    protected $myauthstorage;
    protected $entityManager;
    protected $checkboxService;
    protected $productrepository;
    protected $customerrepository;

    public function __construct($sm) {
        $this->logger = $sm->get('LoggingService');
        $this->myauthstorage = $sm->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $sm->get('config')['pricing_config'];
        $this->entityManager = $sm->get('FFMEntityManager');
        $this->checkboxService = $sm->get('CheckboxService');
        $this->productrepository = $sm->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Product');
        $this->customerrepository = $sm->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Customer');
    }

    /**
     * filters array returned from svc call and array returned from local db and any removed 
     * SKUs that could be in Session scope as well as any saved price overrides.
     * 
     * @param type $restcallitems
     * @param type $customerid
     * @return string
     */
    public function _filter($restcallitems, $customerid) {

        //iterate items
        $this->logger->debug('Retrieved ' . count($restcallitems) . ' ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $merged = array();
        $customer = $this->customerrepository->findCustomer($customerid);

        //add override column for override price
        foreach ($restcallitems as &$item) {
            $item['overrideprice'] = '';
        }

        //now lookup any RowPlusItemsPage rows that are active and add them to the results.
        //take care to only get latest rows and ignoring any rows added previously today.
        $rowPlusItemPages = $this->query(
                ItemsFilterTableArrayService::QUERY_PAGES, $customerid
        );

        $itemPriceOverrides = $this->query(
                ItemsFilterTableArrayService::QUERY_OVERRIDES, $customerid
        );

        $overrideMap = array();
        $foundSomeOverrides = false;

        foreach ($itemPriceOverrides as $price) {
            $override = number_format($price->getOverrideprice() / 100, 2);
            $pid = strval($price->getProduct()->getId());
            $this->logger->info('Found saved overrideprice: ' . $override . ' with productID: ' . $pid);
            $overrideMap[$pid] = $override;
            $foundSomeOverrides = true;
        }

        if (!$foundSomeOverrides) {
            $this->logger->info("No price overrides found!");
        }

        //now allow rows found in DB SO: A row will either be from a User adding the entire row it
        //should override the array from svc when ACTIVE
        //create HashMap of keys - then override
        $map = array();
        
                //get a reference to IDS that have been selected from the table.
        $removedSKUS = $this->retrieveRemovedSkus($customerid);

        $this->logger->info('Found ' . count($removedSKUS) . ' removedSKUs in Session!');

        //foreach ($removedSKUS as $removed) {
            //$this->logger->info('Setting checkbox for table row with SKU: ' . $removed);
        //}

        //create a selected field for each REST call item based on removed SKUs.
        $removedIDS = array();
        foreach($removedSKUS as $product){
            $removedIDS [] = $product->getId();
        }
        
        //first add all items from DB to results
        foreach ($rowPlusItemPages as &$item) {
            //has no wholesale or retail must get related entity
            $adjWholesale = number_format($item->getProduct()->getWholesale() / 100, 2);
            $adjRetail = number_format($item->getProduct()->getRetail() / 100, 2);
            $adjOverrideprice = number_format($item->getOverrideprice() / 100, 2);
            //if override exists - then graph it in.
            if (array_key_exists(strval($item->getProduct()->getId()), $overrideMap)) {
                $adjOverrideprice = $overrideMap[strval($item->getProduct()->getId())];
                $this->logger->info("ItemsFilterTableArrayService: Found Override in map: " . $adjOverrideprice);
            }
            $this->logger->info("ItemsFilterTableArrayService: ProductID: " . $item->getProduct()->getId());
            
            $addSelected = false;
            if (in_array($item->getProduct()->getId(), $removedIDS)) {
                $addSelected = true;
            }
            
            $merged[] = $this->addItem($customer, $item->getProduct(), $adjWholesale, $adjRetail, $adjOverrideprice, $addSelected);
            //add to the map
            $map[$item->getProduct()->getId()] = $item;
        }

        foreach ($restcallitems as &$item) {
            //add checkbox
            if (!in_array($item['id'], $removedIDS)) {
                $item['selected'] = false;
            } else {
                $item['selected'] = true;
            }
            $merged = $this->notInMerge($item, 'id', $map, $merged, $overrideMap);
        }

        return $merged;
    }

    protected function query($q, $customerid) {
        //needs to be aware of admin role because if the User is an admin - they need to see the Products for the selected salesperson.
        return $this->entityManager->getEntityManager()->
                        createQuery($q)->setParameter("customerid", $customerid)->
                        setParameter("created", DateUtils::getDailyCutoff())->
                        setParameter("salesperson", $this->myauthstorage->admin() && !empty($this->myauthstorage->getSalespersonInPlay()) ?
                                $this->myauthstorage->getSalespersonInPlay()->getUsername() :
                                $this->myauthstorage->getUser()->getUsername())
                        ->getResult();
    }

    protected function notInMerge($item, $prop, $skuMap, $dest, $override) {
        if (!array_key_exists($item[$prop], $skuMap)) {
            //apply priceoverride if exists
            return $this->applyOverride($override, $item, $dest);
        }
        return $dest;
    }

    protected function retrieveRemovedSkus($customerid) {
        $userinplay = $this->myauthstorage->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->myauthstorage->getUser();
        }
        return !empty($this->checkboxService->getRemovedIDS($customerid, $userinplay->getUsername())) ?
                $this->checkboxService->getRemovedIDS($customerid, $userinplay->getUsername()) :
                [];
    }
    
    private function addItem(Customer $customer, Product $product, $adjWholesale, $adjRetail, $adjOverrideprice, $addSelected){
        $userproduct = $product->getCustomerUserProduct($customer)->first();
        return array(
                "id" => $product->getId(),
                "productname" => $product->getProductname(),
                "shortescription" => $product->getDescription(),
                "comment" => $userproduct->getComment(),
                "option" => $userproduct->getOption(),
                "qty" => $product->getQty(),
                "wholesale" => $adjWholesale,
                "retail" => $adjRetail,
                "overrideprice" => $adjOverrideprice,
                "uom" => $product->getUom(),
                "sku" => $product->getSku(),
                "selected" => $addSelected,
                "status" => strcmp(strval($product->getStatus()), "1") == 0 ? "Enabled" : "Disabled",
                "saturdayenabled" => strcmp(strval($product->getSaturdayenabled()), "1") == 0 ? "Enabled" : "Disabled",
            );
    }

    protected function applyOverride($overrideMap, $item, $merged) {
        if (array_key_exists(strval($item['id']), $overrideMap)) {
            $item['overrideprice'] = $overrideMap[strval($item['id'])];
        }
        $merged[] = $item;
        return $merged;
    }

}
