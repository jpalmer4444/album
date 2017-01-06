<?php

namespace Ajax\Controller\Sales;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\ItemPriceOverride;
use DataAccess\FFM\Entity\Product;
use DataAccess\FFM\Entity\UserProduct;
use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\Query\Expr\Select;
use Exception;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

/**
 * Description of ItemsController
 *
 * @author jasonpalmer
 */
class ItemsController extends AbstractRestfulController {

    protected $restService;
    protected $logger;
    protected $myauthstorage;
    protected $pricingconfig;
    protected $entityManager;
    protected $itemsFilterService;
    protected $checkboxService;
    protected $productrepository;
    protected $userproductrepository;
    protected $customerrepository;
    protected $rowplusitemspagerepository;
    protected $customerid;
    protected $qb;

    public function onDispatch(MvcEvent $e) {
        Logger::info("ItemsController", __LINE__, "" . $e->getParam("request")->getRequestUri());
        try {
            return parent::onDispatch($e);
        } catch (Exception $exc) {
            Logger::info("ItemsController", __LINE__, $exc->getTraceAsString());
        }
    }

    function getEntityManager($container) {
        return $container->get('FFMEntityManager')->getEntityManager();
    }

    function getRepo($model, $container) {
        return $this->getEntityManager($container)->
                        getRepository($model);
    }

    function getQueryBuilder($container) {
        return $this->getEntityManager($container)->
                        createQueryBuilder();
    }

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->checkboxService = $container->get('CheckboxService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $this->getEntityManager($container);
        $this->itemsFilterService = $container->get('ItemsFilterTableArrayService');
        $this->productrepository = $this->getRepo('DataAccess\FFM\Entity\Product', $container);
        $this->rowplusitemspagerepository = $this->getRepo('DataAccess\FFM\Entity\RowPlusItemsPage', $container);
        $this->customerrepository = $this->getRepo('DataAccess\FFM\Entity\Customer', $container);
        $this->userproductrepository = $this->getRepo('DataAccess\FFM\Entity\UserProduct', $container);
        $this->qb = $this->getQueryBuilder($container);
    }

    //framework calls get when an id parameter is not found in request
    public function getList() {
        Logger::info("ItemsController", __LINE__, 'ItemsController Ajax called.');
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
        switch ($this->params()->fromQuery("myaction")) {
            case "overridePrice" :
            default : {
                    return $this->overridePrice($id);
                }
        }
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

    function ifstrpos($var) {
        return (strpos($var, 'P') !== false);
    }

    function getItemPriceOverride($customerid, $id) {
        $record = new ItemPriceOverride();
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setActive(true);
        $customer = $this->customerrepository->findCustomer($customerid);
        $record->setCustomer($customer);
        $product = $this->productrepository->findProduct(substr($id, 1));
        $record->setProduct($product);
        return $record;
    }
    
    function transaction(){
        $this->entityManager->getConnection()->beginTransaction();
    }
    
    function saveItemPriceOverride($record, $rowIndex){
        try {
                //... do some work
                $salesperson = $this->entityManager->find('DataAccess\FFM\Entity\User', empty($this->myauthstorage->getSalespersonInPlay()) ?
                        $this->myauthstorage->getUser()->getUsername() :
                        $this->myauthstorage->getSalespersonInPlay()->getUsername(), LockMode::PESSIMISTIC_READ);
                $record->setSalesperson($salesperson);
                $this->entityManager->persist($record);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
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
    
    function saveRowPlusItemsPage($record, $rowIndex){
        try {
                //... do some work
                $salesperson = $this->entityManager->find('DataAccess\FFM\Entity\User', empty($this->myauthstorage->getSalespersonInPlay()) ?
                        $this->myauthstorage->getUser()->getUsername() :
                        $this->myauthstorage->getSalespersonInPlay()->getUsername(), LockMode::PESSIMISTIC_READ);
                $record->setSalesperson($salesperson);
                $this->entityManager->merge($record);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
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
    
    function getRowPlusItemsPage($customerid, $id, $overrideprice){
        $record = $this->rowplusitemspagerepository->findRowPlusItemsPage(substr($id, 1));
            $created = new DateTime("now");
            $record->setCreated($created);
            $record->setActive(true);
            $customer = $this->customerrepository->findCustomer($customerid);
            $record->setCustomer($customer);
            if (!empty($overrideprice)) {
                $record->setOverrideprice($overrideprice);
            }
            return $record;
    }

    protected function overridePrice($id) {
        $customerid = $this->params()->fromQuery('customerid');
        $rowIndex = $this->params()->fromQuery('index');
        $overrideprice = $this->params()->fromQuery('overrideprice');
        Logger::info("ItemsController", __LINE__, 'Saving overrideprice: ' . $overrideprice . '.');
        //we can either have an $id that begins with 'P' which needs an ItemPriceOverride
        //OR
        //we can have an $id that begins with 'A' which has a corresponding RowPlusItemsRow.
        if ($this->ifstrpos($id)) {
            //save overridePrice in DB
            $record = $this->getItemPriceOverride($customerid, $id);
            if (!empty($overrideprice)) {
                $record->setOverrideprice($overrideprice);
            }
            $this->transaction();
            $this->saveItemPriceOverride($record, $rowIndex);
        } else {
            //update existing RowPlusItemsPage row
            $record = $this->getRowPlusItemsPage($customerid, $id, $overrideprice);
            $this->transaction();
            $this->saveRowPlusItemsPage($record, $rowIndex);
        }
    }

    protected function getTable() {
        Logger::info("ItemsController", __LINE__, 'Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $this->customerid = $customerid;
        $params = $this->getBaseBySkuParams();
        /*
         * access_log
         * error_log
         * pricing-custom.log
         * pricing-error.log
         * ssl_error_log
         * ssl_request_log
         */
        $params["customerid"] = $customerid;
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($this->pricingconfig['by_sku_base_url'], $method, $params);
        $restcallitemsmerged = [];
        if ($json && array_key_exists($this->pricingconfig['by_sku_object_items_controller'], $json)) {
            //iterate
            //$json[$this->pricingconfig['by_sku_object_items_controller']]
            //and find corresponding rows in DB and insert or update as appropriate.
            $this->sync($json);
            $restcallitemsmerged = $this->itemsFilterService->_filter($json[$this->pricingconfig['by_sku_object_items_controller']], $customerid);
        } else {
            Logger::debug("ItemsController", __LINE__, 'No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
        }
        return new JsonModel(array(
            "data" => $restcallitemsmerged
        ));
    }

    private function sync($json) {
        //now lookup these items in the DB and update if there are discrepancies
        $this->qb->add('select', new Select(array('u')))
                ->add('from', new From('DataAccess\FFM\Entity\Product', 'u'));
        $arr = [];
        foreach ($json[$this->pricingconfig['by_sku_object_items_controller']] as $product) {
            $arr [] = $this->qb->expr()->eq('u.id', "'" . utf8_encode($product['id']) . "'");
        }

        $this->qb->add('where', $this->qb->expr()->orX(
                                implode(" OR ", $arr)
                ))
                ->add('orderBy', new OrderBy('u.productname', 'ASC'));
        $query = $this->qb->getQuery();
        $dbcustomers = $query->getResult();
        Logger::info("ItemsController", __LINE__, 'Found ' . count($dbcustomers) . ' customers in db.');
        $inDb = count($dbcustomers);
        $inSvc = count($json[$this->pricingconfig['by_sku_object_items_controller']]);
        Logger::info("ItemsController", __LINE__, 'Found ' . $inSvc . ' items in svc and ' . $inDb . ' in DB.');
        if ($inDb < $inSvc) {
            //remove every matching row in DB and rewrite them all to guarantee we have latest data
            //in theory this should flush everything out and keep records up-to-date over time.
            $some = false;
            try {
                foreach ($json[$this->pricingconfig['by_sku_object_items_controller']] as $product) {
                    //lookup item with id
                    $cdb = $this->productrepository->find($product['id']);
                    if (!empty($cdb)) {
                        //update existing record
                        $cdb->setSku($product['sku']);
                        $cdb->setProductname($product['productname']);
                        $cdb->setDescription($product['shortescription']);
                        $userproduct = $this->userproductrepository->findUserProduct($this->customerid, $product['id']);
                        if (empty($userproduct)) {
                            $userproduct = new UserProduct();
                            $userProducts = $cdb->getUserProducts();
                            $userProducts->add($userproduct);
                            $customer = $this->customerrepository->findCustomer($this->customerid);
                            $userproduct->setCustomer($customer);
                            $userproduct->setProduct($cdb);
                            $this->userproductrepository->persist($userproduct);
                        }
                        $userproduct->setComment($product['comment']);
                        $userproduct->setOption($product['option']);
                        $cdb->setQty($product['qty']);
                        if (!empty($product['wholesale'])) {
                            $cdb->setWholesale($product['wholesale']);
                        }
                        if (!empty($product['retail'])) {
                            $cdb->setRetail($product['retail']);
                        }
                        $cdb->setUom($product['uom']);
                        $this->userproductrepository->merge($userproduct);
                        $some = true;
                    } else {
                        //insert record because it doesn't exist.
                        $cdb = new Product();
                        $cdb->setId($product['id']);
                        $userproduct = new UserProduct();
                        $userproduct->setComment($product['comment']);
                        $userproduct->setOption($product['option']);
                        $userProducts = $cdb->getUserProducts();
                        $userProducts->add($userproduct);
                        //lookup salesperson
                        $customer = $this->customerrepository->findCustomer($this->customerid);
                        $userproduct->setCustomer($customer);
                        $userproduct->setProduct($cdb);
                        $cdb->setSku($product['sku']);
                        $cdb->setStatus($product['status'] ? true : false);
                        $cdb->setSaturdayenabled($product['saturdayenabled'] ? true : false);
                        $cdb->setProductname($product['productname']);
                        $cdb->setDescription($product['shortescription']);
                        $cdb->setQty($product['qty']);
                        if (!empty($product['wholesale'])) {
                            $cdb->setWholesale($product['wholesale']);
                        }
                        if (!empty($product['retail'])) {
                            $cdb->setRetail($product['retail']);
                        }
                        $cdb->setUom($product['uom']);
                        $this->userproductrepository->persist($userproduct);
                        $some = true;
                    }
                }
                if ($some) {
                    $this->userproductrepository->flush();
                }
            } catch (Exception $exc) {
                var_dump($exc);
            }
        }
    }

    protected function select() {
        $ids = $this->params()->fromQuery('ids');
        $customerid = $this->params()->fromQuery('customerid');
        $userinplay = $this->myauthstorage->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->myauthstorage->getUser();
        }
        if (empty($userinplay)) {
            Logger::info("ItemsController", __LINE__, "UserinPlay is null!");
        }
        foreach ($ids as $id) {
            Logger::info("ItemsController", __LINE__, 'Selecting ' . $id);
            $this->checkboxService->addRemovedID($id, $customerid, $userinplay->getUsername());
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
        $userinplay = $this->myauthstorage->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->myauthstorage->getUser();
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

        $userinplay = $this->myauthstorage->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->myauthstorage->getUser();
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
