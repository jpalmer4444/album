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
    
    public function __construct($container){
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
    }
    
    public function getList()
    {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $params = [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_items_controller'],
            "customerid" => $customerid
        ];
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($this->pricingconfig['by_sku_base_url'], $method, $params);
        if($json && array_key_exists($this->pricingconfig['by_sku_object_items_controller'], $json)){
            $this->logger->debug('Retrieved ' . count($json[$this->pricingconfig['by_sku_object_items_controller']]) . ' ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        }else{
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
            $json[$this->pricingconfig['by_sku_object_items_controller']] = [];
        }
        
        return new JsonModel(array(
            "data" => $json[$this->pricingconfig['by_sku_object_items_controller']]
        ));
    }
    
    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }
    
}
