<?php

namespace Sales\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SalesController extends AbstractActionController{
    
    protected $restService;

    protected $logger;
    
    //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=salespeople
    protected $myauthstorage;
    
    //environment specifics properties/values
    protected $pricingconfig;
    
    public function __construct($container){
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
    }

    public function indexAction() {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_sales_controller'] . '.');
        $params = [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_sales_controller']
        ];
        $url = $this->pricingconfig['by_sku_base_url'];
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($url, $method, $params);
        if(array_key_exists($this->pricingconfig['by_sku_object_sales_controller'], $json)){
            $this->logger->debug('Retrieved ' . count($json[$this->pricingconfig['by_sku_object_sales_controller']]) . ' ' . $this->pricingconfig['by_sku_object_sales_controller'] . '.');
        }else{
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_sales_controller'] . ' items found! Error!');
        }
        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
