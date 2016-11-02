<?php

/**
 */

namespace Sales\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ItemsController extends AbstractActionController {

    protected $logger;
    protected $myauthstorage;
    protected $pricingconfig;
    protected $customerid;
    protected $customername;

    public function __construct($container) {
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
    }

    public function indexAction() {
        
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $this->customerid = $this->params()->fromQuery('customerid');
        $this->customername = $this->params()->fromQuery('customername');

        if (empty($this->customerid) ||
                empty($this->customername)) {
            //must have customerid and customername params or redirect back to /users to retrieve
            //correct params to render this page.
            $params = [
                'controller' => 'UsersController',
                'action' => 'index',
            ];
            return $this->redirect()->toRoute('users', $params, array());
        }

        return new ViewModel(array(
            "customerid" => $this->customerid,
            "customername" => $this->customername
        ));
    }

    public function editAction() {
        
    }

}
