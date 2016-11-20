<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of ProductRepository
 *
 * @author jasonpalmer
 */
class ProductRepositoryImpl extends FFMRepository {

    public function findProduct($id) {
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\Product", $id);
    }

    public function findMaxNegative() {
        $leastID = -1;
        $leastIDRecord = $this
                ->getEntityManager()
                ->createQueryBuilder()
                ->select('product')
                ->from('DataAccess\FFM\Entity\Product', 'product')
                ->orderBy('product.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        if (empty($leastIDRecord) || $leastIDRecord->getId() > -1) {
            $leastID = -1;
        } else {
            $leastID = $leastIDRecord->getId() - 1;
        }
        return $leastID;
    }

}
