<?php
/**
 */

namespace Sales\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ItemsController extends AbstractActionController
{

    protected $logger;
    
    protected $myauthstorage;
    
    protected $pricingconfig;
    
    protected $customerid;
    
    public function __construct($container){
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
    }
    
    public function indexAction()
    {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $this->customerid = $this->params()->fromQuery('customerid');
        
        return new ViewModel(array(
            "customerid" => $this->customerid
        ));
    }
    
    public function editAction(){
        
    }
    
}
