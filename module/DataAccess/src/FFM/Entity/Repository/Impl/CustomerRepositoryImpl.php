<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of CustomerRepository
 *
 * @author jasonpalmer
 */
class CustomerRepositoryImpl extends FFMRepository {
    
    public function findCustomer($id){
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\Customer", $id);
    }
    
}
