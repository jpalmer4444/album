<?php

namespace Ajax\Controller\Sales;

use Application\Service\FFMEntityManagerService;
use Application\Utility\Logger;
use DataAccess\FFM\Entity\ItemPriceOverride;
use DateTime;
use Doctrine\DBAL\LockMode;
use Exception;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\View\Model\JsonModel;

/**
 * Description of ItemsController
 *
 * @author jasonpalmer
 */
class ItemsController extends AbstractRestfulController {

    protected $restService;
    protected $sessionManager;
    protected $pricingconfig;
    protected $entityManager;
    protected $itemsFilterService;
    protected $checkboxService;
    protected $productrepository;
    protected $itempriceoverriderepository;
    protected $customerrepository;
    protected $rowplusitemspagerepository;
    protected $customerid;
    protected $sessionService;
    protected $synchronizationService;

    public function __construct($container) {
        Logger::info("ItemsController", __LINE__, 'Building ItemController (AJAX).');
        $this->restService = $container->get('RestService');
        $this->synchronizationService = $container->get('SynchronizationService');
        $this->checkboxService = $container->get('CheckboxService');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $this->getEntityManager($container);
        $this->itemsFilterService = $container->get('ItemsFilterTableArrayService');
        $this->sessionService = $container->get('SessionService');
        $this->sessionManager = $container->get(SessionManager::class);
        $this->productrepository = $this->getRepo('DataAccess\FFM\Entity\Product', $container);
        $this->rowplusitemspagerepository = $this->getRepo('DataAccess\FFM\Entity\RowPlusItemsPage', $container);
        $this->customerrepository = $this->getRepo('DataAccess\FFM\Entity\Customer', $container);
        $this->itempriceoverriderepository = $this->getRepo('DataAccess\FFM\Entity\ItemPriceOverride', $container);
    }

    public function onDispatch(MvcEvent $e) {
        Logger::info("ItemsController", __LINE__, "" . $e->getParam("request")->getRequestUri());
        try {
            return parent::onDispatch($e);
        } catch (Exception $exc) {
            Logger::info("ItemsController", __LINE__, $exc->getTraceAsString());
        }
    }

    /**
     * Returns an instance of FFMEntityManagerService
     * @param type $container
     * @return FFMEntityManagerService
     */
    private function getEntityManager($container) {
        return $container->get('FFMEntityManager')->getEntityManager();
    }

    private function getRepo($model, $container) {
        return $this->getEntityManager($container)->
                        getRepository($model);
    }

    //framework calls get when an id parameter is not found in request
    /**
     * When an id is <strong>NOT</strong> present in query parameters the Zend Framework will automatically
     * dispatch such a request to the getList() method in the mapped controller. 
     * 
     * This method is used for several cases (based on "myaction" parameter):
     *      1. select
     *          Used 
     * 
     * 
     * ** I am unsure if this is only true when the Request is a Json Request based on Accept header.
     * 
     * @return JsonModel
     */
    public function getList() {

        Logger::info("ItemsController", __LINE__, 'ItemsController Ajax called.');
        $this->forceSessionLogin();
        switch ($this->params()->fromQuery("myaction")) {
            case "select" : {
                    return $this->select();
                }
            case "unselect" : {
                    return $this->unselect();
                }
            case "customerlisttableget" :
            default : {
                    return $this->getTable();
                }
        }
    }

    //framework calls get when an id parameter is found in request
    public function get($id) {
        Logger::info("ItemsController", __LINE__, 'ItemsController Ajax called.');
        $this->forceSessionLogin();
        switch ($this->params()->fromQuery("myaction")) {
            case "overridePrice" :
            default : {
                    return $this->overridePrice($id);
                }
        }
    }

    private function forceSessionLogin() {
        $username = $this->params()->fromQuery("username");
        $session_id = $this->params()->fromQuery("session_id");
        $this->sessionService->login($username, $session_id);
    }

    public function makeRestCall($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

    function transaction() {
        $this->entityManager->getConnection()->beginTransaction();
    }

    function saveItemPriceOverride($record, $rowIndex) {
        try {
            $this->transaction();
            Logger::info("ItemsController", __LINE__, 'Calling saveItemPriceOverride');
            $salesperson = $this->entityManager->find('DataAccess\FFM\Entity\User', empty($this->sessionService->getSalespersonInPlay()) ?
                    $this->sessionService->getUser()->getUsername() :
                    $this->sessionService->getSalespersonInPlay()->getUsername(), LockMode::PESSIMISTIC_READ);
            $record->setSalesperson($salesperson);
            $this->persistRecord($record);
            Logger::info("ItemsController", __LINE__, 'ItemPriceOverride saved.');
            return new JsonModel(array(
                'success' => true, 'index' => $rowIndex, 'overrideprice' => $record->getOverrideprice()
            ));
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            Logger::info("ItemsController", __LINE__, strval($e));
            return new JsonModel(array(
                'success' => false,
            ));
        }
    }

    private function persistRecord($record) {
        
        $this->entityManager->persist($record);
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
        
    }

    function setLatestOverridePriceInactive($customerid, $salespersonusername, $productid, $rowIndex) {
        
        Logger::info("ItemsController", __LINE__, 'find ItemPriceOverride salesperson: ' . $salespersonusername . " ProductId: " . $productid . " CustomerId: " . $customerid);
        
        $record = $this->itempriceoverriderepository->findItemPriceOverride($salespersonusername, $productid, $customerid);
        
        if (!empty($record)) {
            $record->setActive(0);
            $this->itempriceoverriderepository->mergeAndFlush($record);
            $nextrecord = $this->itempriceoverriderepository->findItemPriceOverride($salespersonusername, $productid, $customerid);
        }
        
        Logger::info("ItemsController", __LINE__, 'ItemPriceOverride set inactive.');
        
        return new JsonModel(array(
            'success' => true,
            'index' => $rowIndex,
            'overrideprice' => !empty($nextrecord) ? $nextrecord->getOverrideprice() : ''
        ));
    }

    private function parameter($param) {
        return $this->params()->fromQuery($param);
    }

    private function overridePriceOnItemPriceOverride($id) {

        //test if price is zero
        if (empty($this->parameter('overrideprice'))) {

            return $this->setLatestOverridePriceInactive(
                            $this->parameter('customerid'), $this->parameter('salesperson'), 
                            substr($id, 1), $this->parameter('index')
            );
        }

        //save overridePrice in DB
        $record = $this->createNewItemPriceOverride(
                $this->parameter('customerid'), $id, 
                $this->parameter('overrideprice')
                );

        return $this->saveItemPriceOverride($record, $this->parameter('index'));
    }

    private function createNewItemPriceOverride($customerid, $id, $overrideprice) {
        $record = new ItemPriceOverride();
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setOverrideprice($overrideprice);
        $record->setActive(true);
        $this->setCustomerOnRecord($customerid, $record);
        $this->setProductOnRecord(substr($id, 1), $record);
        return $record;
    }

    private function setCustomerOnRecord($customerid, $record) {
        $customer = $this->customerrepository->findCustomer($customerid);
        $record->setCustomer($customer);
    }

    private function setProductOnRecord($productid, $record) {
        $product = $this->productrepository->findProduct($productid);
        $record->setProduct($product);
    }

    protected function overridePrice($id) {
        //log this action
        Logger::info("ItemsController", __LINE__, 'Saving overrideprice: ' . $this->parameter('overrideprice') . '.');

        //test type of ID to know which kind of record to add
        if ($this->ifRowPlusItemsPageID($id)) {

            return $this->overridePriceOnItemPriceOverride($id);
        } else {
            //update existing RowPlusItemsPage row

            return $this->saveRowPlusItemsPage($id, $this->parameter('index'));
        }
    }

    private function saveRowPlusItemsPage($id, $rowIndex) {

        try {
            //lookup the record
            $record = $this->getRowPlusItemsPage($this->parameter('customerid'), $id, $this->parameter('overrideprice'));
            //start a transaction
            $this->transaction();
            //lookup salesperson
            $salesperson = $this->getSalesperson();
            //set salesperson
            $record->setSalesperson($salesperson);
            $this->entityManager->merge($record);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            Logger::info("ItemsController", __LINE__, 'RowPlusItemsPage saved.');
            return new JsonModel(array(
                'success' => true,
                'index' => $rowIndex,
                'overrideprice' => $record->getOverrideprice()
            ));
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            Logger::info("ItemsController", __LINE__, strval($e));
            return new JsonModel(array(
                'success' => false,
            ));
        }
    }

    private function getSalesperson() {
        return $this->entityManager->find('DataAccess\FFM\Entity\User', empty($this->sessionService->getSalespersonInPlay()) ?
                        $this->sessionService->getUser()->getUsername() :
                        $this->sessionService->getSalespersonInPlay()->getUsername(), LockMode::PESSIMISTIC_READ);
    }

    private function getRowPlusItemsPage($customerid, $id, $overrideprice) {
        $record = $this->rowplusitemspagerepository->findRowPlusItemsPage(substr($id, 1));
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setActive(true);
        $customer = $this->customerrepository->findCustomer($customerid);
        $record->setCustomer($customer);
        if (!empty($overrideprice)) {
            $record->setOverrideprice($overrideprice);
        }else{
            $record->setOverrideprice(NULL);
        }
        return $record;
    }

    private function ifRowPlusItemsPageID($var) {
        return (strpos($var, 'P') !== false);
    }

    protected function getTable() {
        Logger::info("ItemsController", __LINE__, 'Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $this->customerid = $customerid;
        $params = $this->getBaseBySkuParams();
        $params["customerid"] = $customerid;
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->makeRestCall($this->pricingconfig['by_sku_base_url'], $method, $params);
        $restcallitemsmerged = [];
        if ($json && array_key_exists($this->pricingconfig['by_sku_object_items_controller'], $json)) {
            //iterate
            //$json[$this->pricingconfig['by_sku_object_items_controller']]
            //and find corresponding rows in DB and insert or update as appropriate.
            $this->synchronizationService->sync($json, $this->customerid);
            $restcallitemsmerged = $this->itemsFilterService->_filter($json[$this->pricingconfig['by_sku_object_items_controller']], $customerid);
        } else {
            Logger::debug("ItemsController", __LINE__, 'No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
        }
        return new JsonModel(array(
            "data" => $restcallitemsmerged
        ));
    }

    protected function select() {
        $ids = $this->params()->fromQuery('ids');
        $customerid = $this->params()->fromQuery('customerid');
        $userinplay = $this->sessionService->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->sessionService->getUser();
        }
        if (empty($userinplay)) {
            Logger::info("ItemsController", __LINE__, "UserinPlay is null!");
        }
        if (!empty($ids)) {
            foreach ($ids as $id) {
                Logger::info("ItemsController", __LINE__, 'Selecting ' . $id);
                $this->checkboxService->addRemovedID($id, $customerid, $userinplay->getUsername());
            }
        }
        return new JsonModel(array(
            "success" => true
        ));
    }

    protected function unselect() {
        $ids = $this->params()->fromQuery('ids');
        if (empty($ids)) {
            return $this->unselectAll();
        }
        $customerid = $this->params()->fromQuery('customerid');
        //Logger::info(static::class, __LINE__, 'Unselecting ' . $skus);
        $userinplay = $this->sessionService->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->sessionService->getUser();
        }
        foreach ($ids as $id) {
            $this->checkboxService->removeRemovedID($id, $customerid, $userinplay->getUsername());
        }
        return new JsonModel(array(
            "success" => true
        ));
    }

    protected function unselectAll() {
        $customerid = $this->params()->fromQuery('customerid');

        $userinplay = $this->sessionService->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->sessionService->getUser();
        }
        $removedIDS = $this->checkboxService->getRemovedIDS($customerid, $userinplay->getUsername());
        foreach ($removedIDS as $id) {
            if (strpos(get_class($id), 'RowPlusItemsPage') !== false) {
                $this->checkboxService->removeRemovedID("A" . $id->getId(), $customerid, $userinplay->getUsername());
            } else {
                $this->checkboxService->removeRemovedID("P" . $id->getId(), $customerid, $userinplay->getUsername());
            }
        }
        return new JsonModel(array(
            "success" => true
        ));
    }

    private function getBaseBySkuParams() {
        return [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_items_controller']
        ];
    }

}
