<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of ProductRepository
 *
 * @author jasonpalmer
 */
class ProductRepositoryImpl extends FFMRepository {

    protected function findMaxNegative() {
        $leastID =  $this->getEntityManager()->createQueryBuilder()
                ->select('product')
                ->from('DataAccess\FFM\Entity\Product', 'product')
                ->orderBy('product.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        if(empty($leastID) || $leastID > -1){
                    $leastID = -1;
                }
                return $leastID;
    }

}
