<?php

namespace Sales\Controller;

use Application\Service\EntityService;
use Application\Utility\Logger;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SalesController extends AbstractActionController {

    protected $restService;
    protected $logger;
    //https://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=salespeople
    //environment specifics properties/values
    protected $pricingconfig;
    protected $entityManager;
    protected $userrepository;
    protected $userForm;

    public function __construct(
            $container, 
            EntityService $ffmEntityManagerService, 
            AbstractPluginManager $formManager, 
            UserRepositoryImpl $userrepository) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $ffmEntityManagerService->getEntityManager();
        $this->formManager = $formManager;
        $this->userrepository = $userrepository;
    }

    public function indexAction() {
        $request = $this->getRequest();
        $this->userForm = $this->formManager->get('DataAccess\FFM\Entity\Form\UserForm');
        if ($request->isPost()) {
            //for adding a new User/Salesperson - submit POST with salesattrid and fullname query parameters.
            //for editing an existing User/Salesperson submit POST with User/Salesperson existing username only.
            $salespersonusername = $this->params()->fromQuery('username');
            $salesattrid = $this->params()->fromQuery('salesattrid');
            $fullname = $this->params()->fromQuery('fullname');
            $salesperson = !empty($salespersonusername) ? $this->userrepository->findUser($salespersonusername) : new User();
            if (!empty($salesattrid) && !empty($fullname)) {
                $salesperson->setSales_attr_id($salesattrid);
                $salesperson->setSalespersonname($fullname);
            }
            $this->userForm->bind($salesperson);
            $this->userForm->setData($request->getPost());
            if ($this->userForm->isValid()) {
                if (empty($salespersonusername)) {
                    $this->entityManager->persist($salesperson);
                }
                $this->entityManager->flush();
            }
            return new JsonModel(array("success" => true));
        }
        Logger::info("SalesController", __LINE__, 'Retrieving ' . $this->pricingconfig['by_sku_object_sales_controller'] . '.');
        $params = [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_sales_controller']
        ];
        $url = $this->pricingconfig['by_sku_base_url'];
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($url, $method, $params);
        if (array_key_exists($this->pricingconfig['by_sku_object_sales_controller'], $json)) {
            Logger::info("SalesController", __LINE__, 'Retrieved ' . count($json[$this->pricingconfig['by_sku_object_sales_controller']]) . ' ' . $this->pricingconfig['by_sku_object_sales_controller'] . '.');
        } else {
            Logger::info("Salescontroller", __LINE__, 'No ' . $this->pricingconfig['by_sku_object_sales_controller'] . ' items found! Error!');
        }
        //lookup salesperson in DB for every one in REST call.
        if (!empty($json)) {
            //build query for salespeople to retrieve email and phone number
            $q = "SELECT DISTINCT(user.email) as email, user.phone1 as phone, user.salespersonname as salesperson FROM DataAccess\FFM\Entity\User user WHERE user.salespersonname IN (";
            $cnt = count($json["salespeople"]);
            $idx = 0;
            foreach ($json["salespeople"] as $salesperson) {
                $q .= "'" . $salesperson['salesperson'] . "'";
                if (++$idx != $cnt) {
                    $q .= ", ";
                }
            }
            $q .= ") ORDER BY user.username DESC";
            Logger::info("SalesController", __LINE__, "query: " . $q);
            $users = $this->entityManager->createQuery($q)->getResult();
            if (!empty($users)) {

                $usersInSvc = count($json[$this->pricingconfig['by_sku_object_sales_controller']]);
                if ($usersInSvc > count($users)) {

                    //must add any Salespeople not found in DB
                    foreach ($json[$this->pricingconfig['by_sku_object_sales_controller']] as $restsalesperson) {

                        $salespersonid = $restsalesperson['id'];
                        $salespersonname = $restsalesperson['salesperson'];
                    }
                }

                foreach ($users as $user) {
                    //Logger::info("SalesController", __LINE__, var_dump($user));
                    //here we want to iterate $json['salespeople'] and find associated salesperson
                    //and then we want to add email and phone to that salesperson
                }
            }
        }

        return new ViewModel(array(
            "json" => $json,
            "form" => $this->userForm
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
