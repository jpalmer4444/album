<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use DataAccess\FFM\Entity\Product;
use Doctrine\DBAL\LockMode;
use Exception;

/**
 * Description of ProductRepository
 *
 * @author jasonpalmer
 */
class ProductRepositoryImpl extends FFMRepository {

    public function findProduct($id) {
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\Product", $id);
    }

    public function addedProduct() {
        $this->getEntityManager()->getConnection()->beginTransaction();
        try {
            $product = new Product();
            $product->setId($this->findMaxNegative());
            $this->getEntityManager()->getConnection()->commit();
            return $product;
        } catch (Exception $exc) {
            $this->getEntityManager()->getConnection()->rollBack();
            throw $exc;
        }
    }

    private function findMaxNegative() {
        $leastID = -1;
        $leastIDRecord = $this
                ->getEntityManager()
                ->createQueryBuilder()
                ->select('product')
                ->from('DataAccess\FFM\Entity\Product', 'product')
                ->orderBy('product.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->setLockMode(LockMode::OPTIMISTIC)
                ->getOneOrNullResult();
        if (empty($leastIDRecord) || $leastIDRecord->getId() > -1) {
            $leastID = -1;
        } else {
            $leastID = $leastIDRecord->getId() - 1;
        }
        return $leastID;
    }

}
