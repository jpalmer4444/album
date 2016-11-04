<?php

namespace Ajax\Service\Sales;

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

    public function __construct($sm) {
        $this->logger = $sm->get('LoggingService');
        $this->myauthstorage = $sm->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $sm->get('config')['pricing_config'];
        $this->entityManager = $sm->get('FFMEntityManager');
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
        $queryPages = "SELECT rowPlus FROM DataAccess\FFM\Entity\RowPlusItemsPage rowPlus WHERE "
                . "rowPlus._created >= CURRENT_DATE() AND "
                . "rowPlus._active = 1 AND "
                . "rowPlus._customerid = :customerid AND rowPlus._salesperson = :salesperson "
                . "GROUP BY rowPlus.product ORDER BY rowPlus._created DESC";

        $rollPlusItemPages = $this->entityManager->getEntityManager()->
                        createQuery($queryPages)->setParameter("customerid", $customerid)->
                        setParameter("salesperson", $this->myauthstorage->getUser()->
                                getUsername())->getResult();
        
        $queryOverrides = "SELECT override FROM DataAccess\FFM\Entity\ItemPriceOverride override WHERE "
                . "override._created >= CURRENT_DATE() AND "
                . "override._active = 1 AND "
                . "override._customerid = :customerid AND override._salesperson = :salesperson "
                . "GROUP BY override.sku ORDER BY override._created DESC";
        
        $itemPriceOverrides = $this->entityManager->getEntityManager()->
                        createQuery($queryOverrides)->setParameter("customerid", $customerid)->
                        setParameter("salesperson", $this->myauthstorage->getUser()->
                                getUsername())->getResult();
        
        $overrideMap = array();
        foreach ($itemPriceOverrides as $price){
            $overrideMap[strval($price->getSku())] = number_format($price->getOverrideprice() / 100, 2);
        }
        
        //now allow rows found in DB SO: A row will either be from a User adding the entire row it
        //should override the array from svc when ACTIVE
        //create HashMap of keys - then override
        $map = array();
        //first add all items from DB to results
        foreach ($rollPlusItemPages as &$item) {
            $adjWholesale = number_format($item->getWholesale() / 100, 2);
            $adjRetail = number_format($item->getRetail() / 100, 2);
            $adjOverrideprice = number_format($item->getOverrideprice() / 100, 2);
            //if override exists - then graphed it in.
            if(array_key_exists(strval($item->getSku()), $overrideMap)){
                $adjOverrideprice = $overrideMap[strval($item->getSku())];
            }
            $merged[] = array(
                "productname" => $item->getProduct(),
                "shortescription" => $item->getDescription(),
                "comment" => $item->getComment(),
                "option" => $item->getOption(),
                "qty" => $item->getQty(),
                "wholesale" => $adjWholesale,
                "retail" => $adjRetail,
                "overrideprice" => $adjOverrideprice,
                "uom" => $item->getUom(),
                "sku" => $item->getSku(),
                "status" => strcmp(strval($item->getStatus()), "1") == 0 ? "Enabled" : "Disabled",
                "saturdayenabled" => strcmp(strval($item->getSaturdayenabled()), "1") == 0 ? "Enabled" : "Disabled",
                );
            //add to the map
            $map[$item->getSku()] = $item;
            
        }
        
        //get a reference to Session scope SKUs that have been removed from the table.
        $removedSKUS = !empty($this->myauthstorage->getRemovedSKUS($customerid)) ? $this->myauthstorage->getRemovedSKUS($customerid) : [];
        $this->logger->info('Found ' . count($removedSKUS) . ' removedSKUs in Session!');
        //$this->logger->info('Removed ' . $removedSKUS);
        foreach ($removedSKUS as $removed) {
            $this->logger->info('Removed: ' . $removed);
        }
        foreach ($restcallitems as &$item) {
            //only add rest call items when map doesnt have SKU!
            if(!array_key_exists($item['sku'], $map) && empty(in_array($item['sku'], $removedSKUS))){
                $merged[] = $item;
            }
        }

        return $merged;
    }

}
