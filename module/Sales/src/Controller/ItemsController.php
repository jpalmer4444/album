<?php

/**
 */

namespace Sales\Controller;

use Application\Service\LoggingServiceInterface;
use DataAccess\FFM\Entity\PricingOverrideReport;
use DataAccess\FFM\Entity\Product;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DateTime;
use Login\Model\MyAuthStorage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\AbstractPluginManager;
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

    /**
     * 
     * @param LoggingServiceInterface $logger
     * @param MyAuthStorage $myauthstorage
     * @param \Sales\Controller\FormElementManager $formManager
     * @param type $config simple array of environment specific properties
     */
    public function __construct(
            LoggingServiceInterface $logger, 
            MyAuthStorage $myauthstorage, 
            AbstractPluginManager $formManager, 
            UserRepositoryImpl $userrepository, 
            RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository,
            CustomerRepositoryImpl $customerrepository,
            ProductRepositoryImpl $productrepository, 
            $config = NULL
    ) {
        $this->logger = $logger;
        $this->myauthstorage = $myauthstorage;
        $this->pricingconfig = $config;
        $this->formManager = $formManager;
        $this->userrepository = $userrepository;
        $this->rowplusitemspagerepository = $rowplusitemspagerepository;
        $this->customerrepository = $customerrepository;
        $this->productrepository = $productrepository;
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
        $form = $this->formManager->get('RowPlusItemsPageForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $jsonModelArr = array();
            $postData = $request->getPost();
            //manually set required properties not in the form.
            
            $form->setData($postData);
            if ($form->isValid()) {
                $success = true;
                //create a row_plus_items_page_row and create a Product with negative ID in Products table.
                $product = new Product();
                $product->setId($this->productrepository->findMaxNegative());
                
                $record = new RowPlusItemsPage();
                //not used but was supposed to do all the if work below
                //$record->bind($form, $jsonModelArr);
                
                $record->setProduct($product);
                
                $jsonModelArr["sku"] = empty($form->getData()['sku']) ? '' : $form->getData()['sku'];
                if (array_key_exists("sku", $jsonModelArr)) {
                    $product->setSku($jsonModelArr["sku"]);
                }
                $jsonModelArr["productname"] = empty($form->getData()['product']) ? '' : $form->getData()['product'];
                if (array_key_exists("productname", $jsonModelArr)) {
                    $product->setProduct($jsonModelArr["productname"]);
                }
                $jsonModelArr["shortescription"] = empty($form->getData()['description']) ? '' : $form->getData()['description'];
                if (array_key_exists("shortescription", $jsonModelArr)) {
                    $product->setDescription($jsonModelArr["shortescription"]);
                }
                $jsonModelArr["comment"] = empty($form->getData()['comment']) ? '' : $form->getData()['comment'];
                if (array_key_exists("comment", $jsonModelArr)) {
                    $product->setComment($jsonModelArr["comment"]);
                }
                $jsonModelArr["option"] = empty($form->getData()['option']) ? '' : $form->getData()['option'];
                if (array_key_exists("option", $jsonModelArr)) {
                    $product->setOption($jsonModelArr["option"]);
                }
                $jsonModelArr["qty"] = empty($form->getData()['qty']) ? '' : $form->getData()['qty'];
                if (array_key_exists("qty", $jsonModelArr)) {
                    $product->setQty($jsonModelArr["qty"]);
                }
                $jsonModelArr["wholesale"] = empty($form->getData()['wholesale']) ? '' : $form->getData()['wholesale'];
                if (array_key_exists("wholesale", $jsonModelArr)) {
                    $int = filter_var($jsonModelArr["wholesale"], FILTER_SANITIZE_NUMBER_INT);
                    $product->setWholesale($int);
                }
                $jsonModelArr["retail"] = empty($form->getData()['retail']) ? '' : $form->getData()['retail'];
                if (array_key_exists("retail", $jsonModelArr)) {
                    $int2 = filter_var($jsonModelArr["retail"], FILTER_SANITIZE_NUMBER_INT);
                    $product->setRetail($int2);
                }
                $jsonModelArr["overrideprice"] = empty($form->getData()['overrideprice']) ? '' : $form->getData()['overrideprice'];
                if (array_key_exists("overrideprice", $jsonModelArr)) {
                    $int3 = filter_var($jsonModelArr["overrideprice"], FILTER_SANITIZE_NUMBER_INT);
                    $record->setOverrideprice($int3);
                }
                $jsonModelArr["uom"] = empty($form->getData()['uom']) ? '' : $form->getData()['uom'];
                if (array_key_exists("uom", $jsonModelArr)) {
                    $product->setUom($jsonModelArr["uom"]);
                }
                $jsonModelArr["status"] = empty($form->getData()['status']) ? '' : $form->getData()['status'];
                if (array_key_exists("status", $jsonModelArr)) {
                    $status = $jsonModelArr["status"] == "1";
                    $product->setStatus($status);
                }
                $jsonModelArr["saturdayenabled"] = empty($form->getData()['saturdayenabled']) ? '' : $form->getData()['saturdayenabled'];
                if (array_key_exists("saturdayenabled", $jsonModelArr)) {
                    $saturdayenabled = $jsonModelArr["saturdayenabled"] == "1";
                    $product->setSaturdayenabled($saturdayenabled);
                }
                $created = new DateTime("now");
                $record->setCreated($created);
                $product->setCreated($record->getCreated());
                $record->setActive(true);
                $product->setActive($record->getActive());
                $customer = $this->customerrepository->findCustomer($this->customerid);
                $record->setCustomer($customer);
                
                $salesperson = $this->userrepository->findUser(empty($this->myauthstorage->getSalespersonInPlay()) ? $this->myauthstorage->getUser()->getUsername() : $this->myauthstorage->getSalespersonInPlay()->getUsername());
                
                $record->setSalesperson($salesperson);
                //var_dump($record);
                $this->rowplusitemspagerepository->persistAndFlush($record);
                $this->productrepository->persistAndFlush($product);
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

        return new ViewModel(array(
            "customerid" => $this->customerid,
            "customername" => $this->customername,
            "salesperson" => $this->myauthstorage->getUser()->getUsername(),
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
                    $por = new PricingOverrideReport();
                    if (array_key_exists("sku", $obj)) {
                        $por->setSku($obj['sku']);
                    }
                    if (array_key_exists("comment", $obj)) {
                        $por->setComment($obj['comment']);
                    }
                    if (array_key_exists("shortescription", $obj)) {
                        $por->setDescription($obj['shortescription']);
                    }
                    if (array_key_exists("uom", $obj)) {
                        $por->setUom($obj['uom']);
                    }
                    if (array_key_exists("overrideprice", $obj)) {
                        $int1 = filter_var($obj["overrideprice"], FILTER_SANITIZE_NUMBER_INT);
                        $por->setOverrideprice($int1);
                    }
                    if (array_key_exists("productname", $obj)) {
                        $por->setProduct($obj['productname']);
                    }
                    if (array_key_exists("retail", $obj)) {
                        $int2 = filter_var($obj["retail"], FILTER_SANITIZE_NUMBER_INT);
                        $por->setRetail($int2);
                    }
                    $created = new DateTime("now");
                    $por->setCreated($created);
                    $customer = $this->customerrepository->findCustomer($this->customerid);
                    $por->setCustomer($customer);
                    $salesperson = $this->userrepository->findUser($this->myauthstorage->getUser()->getUsername());
                    $por->setSalesperson($salesperson);
                    $this->rowplusitemspagerepository->persistAndFlush($por);
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
