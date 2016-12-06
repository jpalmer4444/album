<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of UserProductRepository
 *
 * @author jasonpalmer
 */
class UserProductRepositoryImpl extends FFMRepository {
    
    public function findUserProduct($customerid, $productid) {
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\UserProduct", array("customer"=> $customerid, "product"=> $productid));
    }
    
}
