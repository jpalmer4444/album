<?php

/**
 */

namespace Sales\Controller;

use Application\Service\LoggingServiceInterface;
use DataAccess\FFM\Entity\PricingOverrideReport;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DataAccess\FFM\Entity\UserProduct;
use DateTime;
use Login\Model\MyAuthStorage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ItemsController extends AbstractActionController {

    protected $logger;
    protected $myauthstorage;
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
    protected $serviceLocator;

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
    public function __construct(LoggingServiceInterface $logger, MyAuthStorage $myauthstorage, 
            AbstractPluginManager $formManager, UserRepositoryImpl $userrepository, 
            RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, CustomerRepositoryImpl $customerrepository, 
            ProductRepositoryImpl $productrepository, UserProductRepositoryImpl $userproductrepository, 
            $config = NULL) {
        $this->logger = $logger;
        $this->myauthstorage = $myauthstorage;
        $this->pricingconfig = $config;
        $this->formManager = $formManager;
        $this->userrepository = $userrepository;
        $this->rowplusitemspagerepository = $rowplusitemspagerepository;
        $this->customerrepository = $customerrepository;
        $this->productrepository = $productrepository;
        $this->userproductrepository = $userproductrepository;
    }

    public function indexAction() {
        
        $this->logger->info('ItemsController 69: Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $this->customerid = urldecode($this->params()->fromQuery('customerid'));
        $this->customername = urldecode($this->params()->fromQuery('customername'));
        $this->companyname = urldecode($this->params()->fromQuery('companyname'));
        
        //redirect if any of the needed parameters are missing!
        
        if (empty($this->customerid) || empty($this->customername) || empty($this->companyname)) {
            //must have customerid and customername params or redirect back to /users to retrieve
            //correct params to render this page.
            $params = [
                'controller' => 'UsersController',
                'action' => 'index',
            ];
            return $this->redirect()->toRoute('users', $params, array());
        }
        
        $form = $this->formManager->get('RowPlusItemsPageForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $jsonModelArr = array();
            $this->logger->info('ItemsController 87: Posting...');
            $postData = $request->getPost();
            $form->setData($postData);
            if ($form->isValid()) {
                $success = true;
                //create a row_plus_items_page_row and create a Product with negative ID in Products table.
                $product = $this->productrepository->addedProduct();
                $userproduct = new UserProduct();
                $userproduct->setCustomer($this->customerrepository->findCustomer($this->customerid));
                $userproduct->setProduct($product);
                $jsonModelArr["id"] = $product->getId();
                $record = new RowPlusItemsPage();
                $record->setLogger($this->logger);
                $record->setProduct($product);
                $jsonModelArr["sku"] = empty($form->getData()['sku']) ? '' : $form->getData()['sku'];
                if (array_key_exists("sku", $jsonModelArr)) {
                    $product->setSku($jsonModelArr["sku"]);
                }
                
                $jsonModelArr["productname"] = empty($form->getData()['product']) ? '' : $form->getData()['product'];
                if (array_key_exists("productname", $jsonModelArr)) {
                    $product->setProductname($jsonModelArr["productname"]);
                }
                
                $jsonModelArr["shortescription"] = empty($form->getData()['description']) ? '' : $form->getData()['description'];
                if (array_key_exists("shortescription", $jsonModelArr)) {
                    $product->setDescription($jsonModelArr["shortescription"]);
                }

                $jsonModelArr["comment"] = empty($form->getData()['comment']) ? '' : $form->getData()['comment'];
                if (array_key_exists("comment", $jsonModelArr)) {
                    $userproduct->setComment($jsonModelArr["comment"]);
                }

                $jsonModelArr["qty"] = '';
                $jsonModelArr["option"] = '';
                $jsonModelArr["wholesale"] = '';
                $jsonModelArr["retail"] = '';
                $this->logger->info('ItemsController 122: Sanitizing...');
                $jsonModelArr["overrideprice"] = empty($form->getData()['overrideprice']) ? '' : $form->getData()['overrideprice'];
                if (array_key_exists("overrideprice", $jsonModelArr)) {
                    $int3 = filter_var($jsonModelArr["overrideprice"], FILTER_SANITIZE_NUMBER_INT);
                    $record->setOverrideprice($int3);
                }
                $jsonModelArr["uom"] = empty($form->getData()['uom']) ? '' : $form->getData()['uom'];
                if (array_key_exists("uom", $jsonModelArr)) {
                    $product->setUom($jsonModelArr["uom"]);
                }
                $jsonModelArr["status"] = "1";
                $product->setStatus(true);
                $jsonModelArr["saturdayenabled"] = "0";
                $created = new DateTime("now");
                $record->setCreated($created);
                $record->setActive(true);
                $customer = $this->customerrepository->findCustomer($this->customerid);
                $record->setCustomer($customer);
                $salesperson = $this->userrepository->findUser(empty($this->myauthstorage->getSalespersonInPlay()) ? $this->myauthstorage->getUser()->getUsername() : $this->myauthstorage->getSalespersonInPlay()->getUsername());

                $record->setSalesperson($salesperson);
                //var_dump($record);
                $this->rowplusitemspagerepository->persistAndFlush($record);
                //$this->productrepository->persistAndFlush($product);
                $userproduct->setProduct($product);
                $this->userproductrepository->persistAndFlush($userproduct);
                $this->logger->info('Saved added row: ' . $record->getId());
                $this->logger->info('Saved added product with negative PK: ' . $product->getId());
                $jsonModelArr["success"] = $success;
            } else {
                //find out why it failed here.
                foreach ($form->getMessages() as $message) {
                    $this->logger->info('Message: ' . print_r($message, true));
                }
            }
            //only return JSON for POSTs (POSTs are AJAX.)
            return new JsonModel($jsonModelArr);
        }

        //add salesperson username to view for report access.
        //this might need to be changed if we want the report to always render the 
        //selected salesperson instead of the logged-in salesperson as is the case when
        //an admin is using the app.

        $customer = $this->customerrepository->findCustomer($this->customerid);

        if (empty($customer)) {
            $params = [
                'controller' => 'UsersController',
                'action' => 'index',
            ];
            $this->logger->info("Redirecting to users page because customer was not found!");
            return $this->redirect()->toRoute('users', $params, array());
        }

        $time = new DateTime();
        $reporttime = $time->format('Y_m_d');

        return new ViewModel(array(
            "customerid" => $this->customerid,
            "reporttime" => $reporttime,
            "customername" => $this->customername,
            "salesperson" => $this->myauthstorage->getUser()->getUsername(),
            "salespersonname" => $this->myauthstorage->getUser()->getSalespersonname(),
            "salespersonemail" => $this->myauthstorage->getUser()->getEmail(),
            "companyname" => urlencode($customer->getCompany()),
            "salespersonphone1" => $this->myauthstorage->getUser()->getPhone1(),
            "salespersonid" => $this->myauthstorage->getUser()->getSales_attr_id(),
            "form" => $form
        ));
    }

    public function reportAction() {
        $this->logger->info('Persisting PDF Report ');
        $this->customerid = $this->params()->fromQuery('customerid');
        $request = $this->getRequest();
        if ($request->isPost()) {

            $counter = 0;
            foreach ($_POST as $key => $value) {
                $this->logger->info($key . ': ' . $value);
                //if KEY is ODD then $value is stringified JSON row object
                if (!($counter % 2 == 0)) {

                    //{"id":792,"sku":"2868","productname":"Clams - Hard Shell, 
                    //  Littleneck, Southern, Farmed, USA, 100ct",
                    //  "shortescription":"Quarter Bushel<br/>\r\nVirginia, USA",
                    //  "comment":null,"qty":1,"wholesale":24.5,"retail":32.67,
                    //  "uom":"Qtr Bushel","option":null,"status":"Enabled",
                    //  "saturdayenabled":1,"overrideprice":""};

                    $obj = json_decode($value, true);
                    $pricingOverrideReport = new PricingOverrideReport();
                    $overrideSet = false;
                    if (array_key_exists("overrideprice", $obj)) {
                        $int1 = $obj['overrideprice'] * 100;
                        $pricingOverrideReport->setOverrideprice($int1);
                        $overrideSet = true;
                    }

                    if (array_key_exists("retail", $obj)) {
                        $int2 = $obj['retail'] * 100;
                        $pricingOverrideReport->setRetail($int2);
                        if (!$overrideSet) {
                            $pricingOverrideReport->setOverrideprice($int2);
                        }
                    }

                    $created = new DateTime("now");
                    $pricingOverrideReport->setCreated($created);
                    $customer = $this->customerrepository->findCustomer($this->customerid);
                    $pricingOverrideReport->setCustomer($customer);
                    $salesperson = $this->userrepository->findUser($this->myauthstorage->getUser()->getUsername());
                    $pricingOverrideReport->setSalesperson($salesperson);
                    $product = $this->productrepository->findProduct($obj['id']);
                    $pricingOverrideReport->setProduct($product);
                    $this->rowplusitemspagerepository->persistAndFlush($pricingOverrideReport);
                }
                $counter++;
            }
        } else {
            $this->logger->info('Ignoring Request that is not a POST!');
        }
        return new JsonModel(array(
            "success" => true
        ));
    }

    protected function validateReport($postArgs) {
        //first check that there is a value for productname
    }

}
