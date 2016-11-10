<?php

namespace Ajax\Controller\Sales;

use DataAccess\FFM\Entity\ItemPriceOverride;
use DateTime;
use Zend\Mvc\Controller\AbstractRestfulController;
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

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->entityManager = $container->get('FFMEntityManager')->getEntityManager();
        $this->itemsFilterService = $container->get('ItemsFilterTableArrayService');
    }

    public function getList() {
        switch ($this->params()->fromQuery("action")) {
            case "overridePrice" : {
                    return $this->overridePrice();
                }
            case "removeRow" : {
                    return $this->removeRow();
                }
            case "customerlisttableget" :
            default : {
                    return $this->getTable();
                }
        }
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

    protected function overridePrice() {
        $customerid = $this->params()->fromQuery('customerid');
        $rowIndex = $this->params()->fromQuery('index');
        $overrideprice = $this->params()->fromQuery('overrideprice');
        $sku = $this->params()->fromQuery('sku');
        $comment = $this->params()->fromQuery('comment');
        $option = $this->params()->fromQuery('option');
        $this->logger->info('Saving overrideprice: ' . $overrideprice . '.');

        //save overridePrice in DB
        $record = new ItemPriceOverride();
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setActive(true);
        $record->setCustomerid($customerid);
        $record->setSku($sku);
        if (!empty($overrideprice)) {
            $int2 = filter_var($overrideprice, FILTER_SANITIZE_NUMBER_INT);
            $record->setOverrideprice($int2);
        }
        if (!empty($option)) {
            $record->setOption($option);
        }
        if (!empty($comment)) {
            $record->setComment($comment);
        }
        $salesperson = $this->entityManager->merge(empty($this->myauthstorage->getSalespersonInPlay()) ? $this->myauthstorage->getUser() : $this->myauthstorage->getSalespersonInPlay());
        $record->setSalesperson($salesperson);
        $this->entityManager->persist($record);
        $this->entityManager->flush();
        return new JsonModel(array(
            'success' => true,
            'index' => $rowIndex,
            'overrideprice' => number_format($record->getOverrideprice() / 100, 2),
            'comment' => $record->getComment(),
            'option' => $record->getOption(),
        ));
    }

    protected function getTable() {
        $this->logger->info('Retrieving ' . $this->pricingconfig['by_sku_object_items_controller'] . '.');
        $customerid = $this->params()->fromQuery('customerid');
        $params = $this->getBaseBySkuParams();
        $params["customerid"] = $customerid;
        $method = $this->pricingconfig['by_sku_method'];
        $json = $this->rest($this->pricingconfig['by_sku_base_url'], $method, $params);
        $restcallitemsmerged = [];
        if ($json && array_key_exists($this->pricingconfig['by_sku_object_items_controller'], $json)) {
            $restcallitemsmerged = $this->itemsFilterService->_filter($json[$this->pricingconfig['by_sku_object_items_controller']], $customerid);
        } else {
            $this->logger->debug('No ' . $this->pricingconfig['by_sku_object_items_controller'] . ' items found.');
        }

        return new JsonModel(array(
            "data" => $restcallitemsmerged
        ));
    }

    protected function removeRow() {
        $sku = $this->params()->fromQuery('sku');
        $customerid = $this->params()->fromQuery('customerid');
        $this->logger->info('Removing SKU ' . $sku . ' and adding to session removedSKUS.');
        $this->myauthstorage->addRemovedSKU($sku, $customerid);
        return new JsonModel(array(
            "success" => $sku
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
