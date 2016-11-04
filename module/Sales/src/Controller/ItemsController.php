<?php

/**
 */

namespace Sales\Controller;

use Application\Service\FFMEntityManagerServiceInterface;
use Application\Service\LoggingServiceInterface;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DateTime;
use Login\Model\MyAuthStorage;
use Sales\Service\PricingReportPersistenceServiceInterface;
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

    /**
     * 
     * @param LoggingServiceInterface $logger
     * @param MyAuthStorage $myauthstorage
     * @param \Sales\Controller\FormElementManager $formManager
     * @param PricingReportPersistenceServiceInterface $pricingReportPersistenceService
     * @param FFMEntityManagerServiceInterface $ffmEntityManagerService
     * @param type $config simple array of environment specific properties
     */
    public function __construct(
            LoggingServiceInterface  $logger, 
            MyAuthStorage $myauthstorage, 
            AbstractPluginManager $formManager,
            PricingReportPersistenceServiceInterface $pricingReportPersistenceService,
            FFMEntityManagerServiceInterface $ffmEntityManagerService,
            $config = NULL
            ) {
        $this->logger = $logger;
        $this->myauthstorage = $myauthstorage;
        $this->pricingconfig = $config;
        $this->formManager = $formManager;
        $this->pricingReportPersistenceService = $pricingReportPersistenceService;
        $this->entityManager = $ffmEntityManagerService->getEntityManager();
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
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $success = true;
                $record = new RowPlusItemsPage();
                $jsonModelArr["sku"] = empty($form->getData()['sku']) ? '' : $form->getData()['sku'];
                if (array_key_exists("sku", $jsonModelArr)) {
                    $record->setSku($jsonModelArr["sku"]);
                }
                $jsonModelArr["productname"] = empty($form->getData()['product']) ? '' : $form->getData()['product'];
                if (array_key_exists("productname", $jsonModelArr)) {
                    $record->setProduct($jsonModelArr["productname"]);
                }
                $jsonModelArr["shortescription"] = empty($form->getData()['description']) ? '' : $form->getData()['description'];
                if (array_key_exists("shortescription", $jsonModelArr)) {
                    $record->setDescription($jsonModelArr["shortescription"]);
                }
                $jsonModelArr["comment"] = empty($form->getData()['comment']) ? '' : $form->getData()['comment'];
                if (array_key_exists("comment", $jsonModelArr)) {
                    $record->setComment($jsonModelArr["comment"]);
                }
                $jsonModelArr["option"] = empty($form->getData()['option']) ? '' : $form->getData()['option'];
                if (array_key_exists("option", $jsonModelArr)) {
                    $record->setOption($jsonModelArr["option"]);
                }
                $jsonModelArr["qty"] = empty($form->getData()['qty']) ? '' : $form->getData()['qty'];
                if (array_key_exists("qty", $jsonModelArr)) {
                    $record->setQty($jsonModelArr["qty"]);
                }
                $jsonModelArr["wholesale"] = empty($form->getData()['wholesale']) ? '' : $form->getData()['wholesale'];
                if (array_key_exists("wholesale", $jsonModelArr)) {
                    $int = filter_var($jsonModelArr["wholesale"], FILTER_SANITIZE_NUMBER_INT);
                    $record->setWholesale($int);
                }
                $jsonModelArr["retail"] = empty($form->getData()['retail']) ? '' : $form->getData()['retail'];
                if (array_key_exists("retail", $jsonModelArr)) {
                    $int2 = filter_var($jsonModelArr["retail"], FILTER_SANITIZE_NUMBER_INT);
                    $record->setRetail($int2);
                }
                $jsonModelArr["overrideprice"] = empty($form->getData()['overrideprice']) ? '' : $form->getData()['overrideprice'];
                if (array_key_exists("overrideprice", $jsonModelArr)) {
                    $int3 = filter_var($jsonModelArr["overrideprice"], FILTER_SANITIZE_NUMBER_INT);
                    $record->setOverrideprice($int3);
                }
                $jsonModelArr["uom"] = empty($form->getData()['uom']) ? '' : $form->getData()['uom'];
                if (array_key_exists("uom", $jsonModelArr)) {
                    $record->setUom($jsonModelArr["uom"]);
                }
                $jsonModelArr["status"] = empty($form->getData()['status']) ? '' : $form->getData()['status'];
                if (array_key_exists("status", $jsonModelArr)) {
                    $status = $jsonModelArr["status"] == "1";
                    $record->setStatus($status);
                }
                $jsonModelArr["saturdayenabled"] = empty($form->getData()['saturdayenabled']) ? '' : $form->getData()['saturdayenabled'];
                if (array_key_exists("saturdayenabled", $jsonModelArr)) {
                    $saturdayenabled = $jsonModelArr["saturdayenabled"] == "1";
                    $record->setSaturdayenabled($saturdayenabled);
                }
                $created = new DateTime("now");
                $record->setCreated($created);
                $record->setActive(true);
                $record->setCustomerid($this->customerid);
                $salesperson = $this->entityManager->merge($this->myauthstorage->getUser());
                $record->setSalesperson($salesperson);
                $this->entityManager->persist($record);
                $this->entityManager->flush();
                $this->logger->info('Saved added row: ' . $record->getId());
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

    public function buildInputSpec($inputFilter) {
        
    }

    public function reportAction() {
        
    }

}
