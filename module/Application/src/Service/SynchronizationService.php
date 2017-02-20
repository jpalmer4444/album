<?php

namespace Application\Service;

use DataAccess\FFM\Entity\Product;
use DataAccess\FFM\Entity\UserProduct;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\Query\Expr\Select;
use Exception;
use Interop\Container\ContainerInterface;

/**
 * Description of SynchronizationService
 *
 * @author jasonpalmer
 */
class SynchronizationService extends BaseService{
    
    protected $pricingconfig;
    
    protected $qb;
    
    protected $productrepository;
    
    protected $userproductrepository;
    
    protected $customerrepository;
    
    public function __construct(ContainerInterface $container) {
        $this->qb = $container->get('FFMEntityManager')->getEntityManager()->
                        createQueryBuilder();
        $this->userproductrepository = $this->getRepo('DataAccess\FFM\Entity\UserProduct', $container);
        $this->productrepository = $this->getRepo('DataAccess\FFM\Entity\Product', $container);
        $this->customerrepository = $this->getRepo('DataAccess\FFM\Entity\Customer', $container);
        $this->pricingconfig = $container->get('config')['pricing_config'];
    }
    
    private function getRepo($model, $container) {
        return $container->get('FFMEntityManager')->getEntityManager()->
                        getRepository($model);
    }
    
    public function sync($json, $customerid) {
        $some = FALSE;
        //now lookup these items in the DB and update if there are discrepancies
        $this->qb->add('select', new Select(array('u')))
                ->add('from', new From('DataAccess\FFM\Entity\Product', 'u'));
        $arr = [];
        foreach ($json[$this->pricingconfig['by_sku_object_items_controller']] as $product) {
            $some = TRUE;
            $arr [] = $this->qb->expr()->eq('u.id', "'" . utf8_encode($product['id']) . "'");
        }

        if (empty($some)) {
            $this->log("SynchronizationService", __LINE__, 'No items found to sync');
            return;
        }

        $this->qb->add('where', $this->qb->expr()->orX(
                                implode(" OR ", $arr)
                ))
                ->add('orderBy', new OrderBy('u.productname', 'ASC'));
        $query = $this->qb->getQuery();
        $dbcustomers = $query->getResult();
        $this->log("SynchronizationService", __LINE__, 'Found ' . count($dbcustomers) . ' customers in db.');
        $inDb = count($dbcustomers);
        $inSvc = count($json[$this->pricingconfig['by_sku_object_items_controller']]);
        $this->log("SynchronizationService", __LINE__, 'Found ' . $inSvc . ' items in svc and ' . $inDb . ' in DB.');
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
                        $userproduct = $this->userproductrepository->findUserProduct($customerid, $product['id']);
                        if (empty($userproduct)) {
                            $userproduct = new UserProduct();
                            $userProducts = $cdb->getUserProducts();
                            $userProducts->add($userproduct);
                            $customer = $this->customerrepository->findCustomer($customerid);
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
                        $customer = $this->customerrepository->findCustomer($customerid);
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
                $this->log("SynchronizationService", __LINE__, 'Selecting ' . var_dump($exc, true));
            }
        }
    }
    
}
