<?php

/**
 */

namespace Sales\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController {

    const ID = "jpalmer";
    const PASSWORD = "goodbass";
    const OBJECT = "customers";
    const QUERY_SALESPERSON_BY_ID = "SELECT user FROM DataAccess\FFM\Entity\User user WHERE user.sales_attr_id = :sales_attr_id AND user.salespersonname = :salespersonname";

    protected $restService;
    protected $logger;
    protected $myauthstorage;
    protected $pricingconfig;
    protected $entityManager;

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $container->get('FFMEntityManager');
    }

    public function indexAction() {
        //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=customers&salespersonid=183
        $requestedsalespersonid = $this->params()->fromQuery('salespersonid');
        $requestedsalespersonname = $this->params()->fromQuery('salesperson');
        if (isset($requestedsalespersonid) && isset($requestedsalespersonname) && $this->myauthstorage->admin()) {
            $salespersonid = $requestedsalespersonid;
            //lookup salesperson by id and set in Session
            //now lookup the Salesperson they are impersonating...
            $salesperson = $this->entityManager->getEntityManager()->
                    createQuery(UsersController::QUERY_SALESPERSON_BY_ID)->
                    setParameter("sales_attr_id", $salespersonid)->setParameter("salespersonname", $requestedsalespersonname)->getResult();
            //we have multiple salespersons here possibly - so match it to logged in username
            $this->myauthstorage->addSalespersonInPlay($salesperson[0]);
        } else if($this->myauthstorage->admin() && !empty($this->myauthstorage->getSalespersonInPlay())){
            $salespersonid = $this->myauthstorage->getSalespersonInPlay()->getSales_attr_id();
        } else {
            $salespersonid = $this->myauthstorage->getUser()->getSales_attr_id();
        }

        $this->logger->info('Retrieving Salespeople. ID: ' . $salespersonid);
        
        $params = [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_users_controller'],
            "salespersonid" => $salespersonid
        ];

        $url = $this->pricingconfig['by_sku_base_url'];
        $method = $this->pricingconfig['by_sku_method'];

        $json = $this->rest($url, $method, $params);
        $this->logger->info('Retrieved #' . count($json) . ' ' . $this->pricingconfig['by_sku_object_users_controller'] . '.');

        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
