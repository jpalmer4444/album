<?php

namespace Ajax\Controller\Sales;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Description of ItemsController
 *
 * @author jasonpalmer
 */
class ItemsController extends AbstractRestfulController {

    protected $restService;
    protected $logger;
    protected $myauthstorage;
    protected $pricingconfig;
    protected $entityManager;
    protected $itemsFilterService;

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $container->get('FFMEntityManager');
        $this->itemsFilterService = $container->get('ItemsFilterTableArrayService');
    }

    public function getList() {
        switch ($this->params()->fromQuery("action")) {
            case "overridePrice" : {
                return $this->overridePrice();
            }
            case "removeRow" : {
                return $this->removeRow();
            }
            case "customerlisttableget" :
            default : {
                return $this->getTable();
            }
        }
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }
    
    protected function overridePrice(){
        
        //save overridePrice in DB
        
        
        
    }

    protected function getTable() {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $params = $this->getBaseBySkuParams();
        $params["customerid"] = $customerid;
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($this->pricingconfig['by_sku_base_url'], $method, $params);
        $restcallitemsmerged = [];
        if ($json && array_key_exists($this->pricingconfig['by_sku_object_items_controller'], $json)) {
            $restcallitemsmerged = $this->itemsFilterService->_filter($json[$this->pricingconfig['by_sku_object_items_controller']], $customerid);
        } else {
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
        }

        return new JsonModel(array(
            "data" => $restcallitemsmerged
        ));
    }
    
    protected function removeRow() {
        $sku = $this->params()->fromQuery('sku');
        $customerid = $this->params()->fromQuery('customerid');
        $this->logger->info('Removing SKU ' . $sku . ' and adding to session removedSKUS.');
        $this->myauthstorage->addRemovedSKU($sku, $customerid);
        return new JsonModel(array(
            "success" => $sku
        ));
    }
    
    private function getBaseBySkuParams(){
        return [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_items_controller']
        ];
    }

}
