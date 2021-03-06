<?php

namespace Sales\Controller;

/**
 */

use Application\Controller\BaseController;
use Application\Service\LoggingService;
use Application\Service\LoggingServiceInterface;
use Application\Service\SessionService;
use Application\Utility\Logger;
use DataAccess\FFM\Entity\Customer;
use DataAccess\FFM\Entity\PricingOverrideReport;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\PricingOverrideReportRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DateTime;
use Login\Model\MyAuthStorage;
use Sales\Service\SalesFormService;
use Zend\Form\Form;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ItemsController extends BaseController {

    protected $logger;
    protected $sessionService;
    protected $pricingconfig;
    protected $customerid;
    protected $customername;
    protected $inputFilter;
    protected $formManager;
    protected $entityManager;
    protected $pricingReportPersistenceService;
    protected $rowplusitemspagerepository;
    protected $userrepository;
    protected $customerrepository;
    protected $productrepository;
    protected $userproductrepository;
    protected $pricingoverridereportrepository;
    protected $serviceLocator;
    protected $salesFormService;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * 
     * @param LoggingServiceInterface $logger
     * @param MyAuthStorage $myauthstorage
     * @param \Sales\Controller\FormElementManager $formManager
     * @param type $config simple array of environment specific properties
     */
    public function __construct(
            LoggingService $logger, 
            SessionService $sessionService, 
            AbstractPluginManager $formManager, 
            UserRepositoryImpl $userrepository, 
            RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, 
            CustomerRepositoryImpl $customerrepository, 
            ProductRepositoryImpl $productrepository, 
            UserProductRepositoryImpl $userproductrepository, 
            PricingOverrideReportRepositoryImpl $pricingoverridereportrepository, 
            SalesFormService $salesFormService, 
            $config = NULL
            ) {
        $this->logger = $logger;
        $this->sessionService = $sessionService;
        $this->pricingconfig = $config;
        $this->formManager = $formManager;
        $this->userrepository = $userrepository;
        $this->rowplusitemspagerepository = $rowplusitemspagerepository;
        $this->customerrepository = $customerrepository;
        $this->productrepository = $productrepository;
        $this->userproductrepository = $userproductrepository;
        $this->salesFormService = $salesFormService;
        $this->pricingoverridereportrepository = $pricingoverridereportrepository;
    }

    public function indexAction() {
        $this->setLocals();
        if (empty($this->customerid) || empty($this->customername) || empty($this->companyname)) {
            return $this->redirect()->toRoute('users', ['controller' => 'UsersController','action' => 'index'], array());
        }
        $form = $this->formManager->get('RowPlusItemsPageForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            return $this->salesFormService->assembleRowPlusItemsPageAndArray($this->sessionService, $this->customerrepository, $this->userrepository, $this->rowplusitemspagerepository, $form, array(), $this->customerid);
        }
        $customer = $this->customerrepository->findCustomer($this->customerid);
        if (empty($customer)) {
            Logger::info("ItemsController", __LINE__, "Redirecting to users page because customer was not found!");
            return $this->redirect()->toRoute('users', ['controller' => 'UsersController','action' => 'index'], array());
        }
        $time = new DateTime();
        return new ViewModel($this->assembleViewModel($time->format('Y_m_d'), $this->sessionService->getSalespersonInPlay(), $customer, $form));
    }

    public function reportAction() {
        $this->customerid = $this->params()->fromQuery('customerid');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $counter = 0;
            foreach ($_POST as $key => $value) {
                Logger::info("ItemsController", __LINE__, $key . ': ' . $value);
                //if KEY is ODD then $value is stringified JSON row object
                if (!($counter % 2 == 0)) {
                    $obj = json_decode($value, true);
                    $pricingOverrideReport = new PricingOverrideReport();
                    if (array_key_exists("overrideprice", $obj)) {
                        $pricingOverrideReport->setOverrideprice($obj['overrideprice']);
                        $overrideSet = true;
                    }
                    if (array_key_exists("retail", $obj)) {
                        $pricingOverrideReport->setRetail($obj['retail']);
                    }
                    $created = new DateTime("now");
                    $pricingOverrideReport->setCreated($created);
                    $customer = $this->customerrepository->findCustomer($this->customerid);
                    $pricingOverrideReport->setCustomer($customer);
                    $salesperson = $this->userrepository->findUser($this->sessionService->getUser()->getUsername());
                    $pricingOverrideReport->setSalesperson($salesperson);
                    if (strpos($obj['id'], 'P') !== false){
                        $product = $this->productrepository->findProduct(substr($obj['id'], 1));
                        $pricingOverrideReport->setProduct($product);
                    }else{
                        $rowplusitemspage = $this->rowplusitemspagerepository->findRowPlusItemsPage(substr($obj['id'], 1));
                        $pricingOverrideReport->setRowPlusItemsPage($rowplusitemspage);
                    }
                    $this->pricingoverridereportrepository->persistAndFlush($pricingOverrideReport);
                }
                $counter++;
            }
        } else {
            Logger::info("ItemsController", __LINE__, 'Ignoring Request that is not a POST!');
        }
        return new JsonModel(array(
            "success" => true
        ));
    }
    
    private function assembleViewModel($reporttime, $salespersoninplay, Customer $customer, Form $form){
        return array(
            "username" => $this->sessionService->getUser()->getUsername(),
            "session_id" => $this->sessionService->getSessionId(),
            "customerid" => $this->customerid,
            "reporttime" => $reporttime,
            "customername" => $this->customername,
            "salesperson" => $this->sessionService->getUserOrSalespersonInPlay()->getUsername(),
            "salespersonname" => empty($salespersoninplay) ? $this->sessionService->getUser()->getSalespersonname() : $salespersoninplay->getSalespersonname(),
            "salespersonemail" => empty($salespersoninplay) ? $this->sessionService->getUser()->getEmail() : $salespersoninplay->getEmail(),
            "companyname" => urlencode($customer->getCompany()),
            "companynamehtml" => $customer->getCompany(),
            "salespersonphone1" => empty($salespersoninplay) ? $this->sessionService->getUser()->getPhone1() : $salespersoninplay->getPhone1(),
            "salespersonid" => empty($salespersoninplay) ? $this->sessionService->getUser()->getSales_attr_id() : $salespersoninplay->getSales_attr_id(),
            "form" => $form,
            "roles" => $this->sessionService->getRoles(),
                );
    }
    
    private function setLocals(){
        $this->customerid = urldecode($this->params()->fromQuery('customerid'));
        $this->customername = urldecode($this->params()->fromQuery('customername'));
        $this->companyname = urldecode($this->params()->fromQuery('companyname'));
    }

}
