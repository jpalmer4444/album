<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of UserRepository
 *
 * @author jasonpalmer
 */
class UserRepositoryImpl extends FFMRepository {
    
    const QUERY_SALESPERSON_BY_NAME_AND_SALES_ATTR_ID = <<<'EOT'
            SELECT user FROM DataAccess\FFM\Entity\User user = :sales_attr_id AND 
            user.salespersonname = :salespersonname
EOT;
    
    public function findBySalesPersonNameAndSalespersonId($salespersonid, $salespersonname) {
        return $this->getEntityManager()->
                    createQuery(UserRepositoryImpl::QUERY_SALESPERSON_BY_NAME_AND_SALES_ATTR_ID)->
                    setParameter("sales_attr_id", $salespersonid)->
                setParameter("salespersonname", $salespersonname)->
                getResult();
    }
    
    public function findUser($username){
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\User", $username);
    }
    
    
}
