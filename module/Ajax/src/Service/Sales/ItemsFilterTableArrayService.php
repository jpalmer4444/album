<?php

namespace Ajax\Service\Sales;

use DateTime;
use DateTimeZone;

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

    public function __construct($sm) {
        $this->logger = $sm->get('LoggingService');
        $this->myauthstorage = $sm->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $sm->get('config')['pricing_config'];
        $this->entityManager = $sm->get('FFMEntityManager');
        $this->checkboxService = $sm->get('CheckboxService');
        $this->productrepository = $sm->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Product');
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
            $this->logger->info('Found saved overrideprice: ' + $override);
            $overrideMap[strval($price->getSku())] = $override;
            $foundSomeOverrides = true;
        }

        if (!$foundSomeOverrides) {
            $this->logger->info("No price overrides found!");
        }

        //now allow rows found in DB SO: A row will either be from a User adding the entire row it
        //should override the array from svc when ACTIVE
        //create HashMap of keys - then override
        $map = array();
        //first add all items from DB to results
        foreach ($rowPlusItemPages as &$item) {
            $adjWholesale = number_format($item->getWholesale() / 100, 2);
            $adjRetail = number_format($item->getRetail() / 100, 2);
            $adjOverrideprice = number_format($item->getOverrideprice() / 100, 2);
            //if override exists - then graphed it in.
            if (array_key_exists(strval($item->getSku()), $overrideMap)) {
                $adjOverrideprice = $overrideMap[strval($item->getSku())];
            }
            $this->logger->info("ItemsFilterTableArrayService: ProductID: " . $item->getProduct());
            $merged[] = $this->addItem($item->getProduct(), $adjWholesale, $adjRetail, $adjOverrideprice);
            //add to the map
            $map[$item->getSku()] = $item;
        }

        //get a reference to Session scope SKUs that have been removed from the table.
        $removedSKUS = $this->retrieveRemovedSkus($customerid);

        $this->logger->info('Found ' . count($removedSKUS) . ' removedSKUs in Session!');

        //foreach ($removedSKUS as $removed) {
            //$this->logger->info('Setting checkbox for table row with SKU: ' . $removed);
        //}

        //create a selected field for each REST call item based on removed SKUs.

        foreach ($restcallitems as &$item) {
            //add checkbox
            if (!in_array($item['id'], $removedSKUS)) {
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
                        setParameter("created", $this->getDailyCutoff())->
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
    
    private function addItem(\DataAccess\FFM\Entity\Product $product, $adjWholesale, $adjRetail, $adjOverrideprice){
        return array(
                "productname" => $product->getProduct(),
                "shortescription" => $product->getDescription(),
                "comment" => $product->getComment(),
                "option" => $product->getOption(),
                "qty" => $product->getQty(),
                "wholesale" => $adjWholesale,
                "retail" => $adjRetail,
                "overrideprice" => $adjOverrideprice,
                "uom" => $product->getUom(),
                "sku" => $product->getSku(),
                "status" => strcmp(strval($product->getStatus()), "1") == 0 ? "Enabled" : "Disabled",
                "saturdayenabled" => strcmp(strval($product->getSaturdayenabled()), "1") == 0 ? "Enabled" : "Disabled",
            );
    }

    private function getDailyCutoff() {
        $pre1pm = false;
        if (date('H') < 13) {
            $pre1pm = true;
        }
        if ($pre1pm) {
            //it is before 1:00PM UTC now - so set the query to retrieve all rows since yesterday at 1:00PM
            return $this->fromOneToOne();
        } else {
            //it is after 1:00PM UTC now - so set the query to retrieve all rows since today at 1:00PM
            return $this->fromOne();
        }
    }
    
    private function fromOne(){
        $date = strtotime('today');
            $time = "13:00:00"; //overwrite time to 1:00 if it is after 1:00.
            $tz_string = "US/Samoa"; // Use one from list of TZ names http://php.net/manual/en/timezones.php UTC?
            $tz_object = new DateTimeZone($tz_string);
            $datetime = new DateTime();
            $datetime->setTimestamp($date);
            $datetime->setTimezone($tz_object);
            return $datetime;
    }

    private function fromOneToOne() {
        $date = strtotime('today -1 day');
        $time = "13:00:00"; //overwrite time to 1:00 if it is after 1:00.
        $tz_string = "US/Samoa"; // Use one from list of TZ names http://php.net/manual/en/timezones.php UTC?
        $tz_object = new DateTimeZone($tz_string);
        $datetime = new DateTime();
        $datetime->setTimestamp($date);
        $datetime->setTimezone($tz_object);
        return $datetime;
    }

    protected function applyOverride($overrideMap, $item, $merged) {
        if (array_key_exists(strval($item['sku']), $overrideMap)) {
            $item['overrideprice'] = $overrideMap[strval($item['sku'])];
        }
        $merged[] = $item;
        return $merged;
    }

}
