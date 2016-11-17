<?php

namespace Sales\Controller;

use Application\Service\FFMEntityManagerServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SalesController extends AbstractActionController {

    protected $restService;
    protected $logger;
    //https://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=salespeople
    protected $myauthstorage;
    //environment specifics properties/values
    protected $pricingconfig;
    protected $entityManager;

    public function __construct($container, FFMEntityManagerServiceInterface $ffmEntityManagerService) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $ffmEntityManagerService->getEntityManager();
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
        if (array_key_exists($this->pricingconfig['by_sku_object_sales_controller'], $json)) {
            $this->logger->debug('Retrieved ' . count($json[$this->pricingconfig['by_sku_object_sales_controller']]) . ' ' . $this->pricingconfig['by_sku_object_sales_controller'] . '.');
        } else {
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_sales_controller'] . ' items found! Error!');
        }

        //lookup salesperson in DB for every one in REST call.
        if (!empty($json)) {
            //build query for salespeople to retrieve email and phone number
            $q = "SELECT DISTINCT(user.email) as email, user.phone1 as phone, user.salespersonname as salesperson FROM DataAccess\FFM\Entity\User user WHERE user.salespersonname IN (";
            $cnt = count($json["salespeople"]);
            $idx = 0;
            foreach ($json["salespeople"] as $salesperson) {
                $q .= "'" . $salesperson['salesperson'] . "'";
                if(++$idx != $cnt){
                    $q .= ", ";
                }
            }
            $q .= ") ORDER BY user.username DESC";
            $this->logger->info("query: " . $q);
            $users = $this->entityManager->createQuery($q)->getResult();
            if(!empty($users)){
                foreach($users as $user){
                    //$this->logger->info(var_dump($user));
                    //here we want to iterate $json['salespeople'] and find associated salesperson
                    //and then we want to add email and phone to that salesperson
                }
            }
        }

        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
