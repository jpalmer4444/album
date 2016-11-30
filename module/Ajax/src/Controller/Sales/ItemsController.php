<?php

namespace Ajax\Controller\Sales;

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
    protected $customerid;
    protected $qb;

    public function onDispatch(MvcEvent $e) {
        $this->logger->info("AjaxItemController found! ");
        //if(stringContains("myaction=overridePrice")){
        //var_dump($e->getRequest());
        //}
        $this->logger->info("" . $e->getParam("request")->getRequestUri());
        try {
            return parent::onDispatch($e);
        } catch (Exception $exc) {
            $this->logger->info($exc->getTraceAsString());
        }
    }

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->checkboxService = $container->get('CheckboxService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $container->get('FFMEntityManager')->getEntityManager();
        $this->itemsFilterService = $container->get('ItemsFilterTableArrayService');
        $this->productrepository = $container->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Product');
        $this->customerrepository = $container->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Customer');
        $this->userproductrepository = $container->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\UserProduct');
        $this->qb = $container->get('FFMEntityManager')->
                        getEntityManager()->createQueryBuilder();
    }

    //framework calls get when an id parameter is not found in request
    public function getList() {
        $this->logger->info('ItemsController Ajax called.');

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
        $this->logger->info('ItemsController Ajax called.');

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

    protected function overridePrice($id) {
        $customerid = $this->params()->fromQuery('customerid');
        $rowIndex = $this->params()->fromQuery('index');
        $overrideprice = $this->params()->fromQuery('overrideprice');
        $this->logger->info('Saving overrideprice: ' . $overrideprice . '.');
        //save overridePrice in DB
        $record = new ItemPriceOverride();
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setActive(true);
        $customer = $this->customerrepository->findCustomer($customerid);
        $record->setCustomer($customer);
        $product = $this->productrepository->findProduct($id);
        $record->setProduct($product);
        if (!empty($overrideprice)) {
            $int2 = filter_var($overrideprice, FILTER_SANITIZE_NUMBER_INT);
            $record->setOverrideprice($int2);
        }
        $this->entityManager->getConnection()->beginTransaction(); // suspend auto-commit
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
                'overrideprice' => number_format($record->getOverrideprice() / 100, 2)
            ));
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error(strval($e));
            return new JsonModel(array(
                'success' => false,
            ));
        }
    }

    protected function getTable() {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $this->customerid = $customerid;
        $params = $this->getBaseBySkuParams();
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
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
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
        $this->logger->info('Found ' . count($dbcustomers) . ' customers in db.');
        $inDb = count($dbcustomers);
        $inSvc = count($json[$this->pricingconfig['by_sku_object_items_controller']]);
        $this->logger->info('Found ' . $inSvc . ' items in svc and ' . $inDb . ' in DB.');
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
                            $int1 = $product['wholesale'] * 100;
                            $cdb->setWholesale($int1);
                        }
                        if (!empty($product['retail'])) {
                            $int2 = $product['retail'] * 100;
                            $cdb->setRetail($int2);
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
                            $int1 = $product['wholesale'] * 100;
                            $cdb->setWholesale($int1);
                        }
                        if (!empty($product['retail'])) {
                            $int2 = $product['retail'] * 100;
                            $cdb->setRetail($int2);
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
            $this->logger->info("UserinPlay is null!");
        }
        foreach ($ids as $id) {
            $this->logger->info('Selecting ' . $id);
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
        //$this->logger->info('Unselecting ' . $skus);
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
        $this->logger->info('UnSelecting All.');
        $userinplay = $this->myauthstorage->getSalespersonInPlay();
        if (empty($userinplay)) {
            $userinplay = $this->myauthstorage->getUser();
        }
        $removedIDS = $this->checkboxService->getRemovedIDS($customerid, $userinplay->getUsername());
        foreach ($removedIDS as $id) {
            $this->checkboxService->removeRemovedID($id, $customerid, $userinplay->getUsername());
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
