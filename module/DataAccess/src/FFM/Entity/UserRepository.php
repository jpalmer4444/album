<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 *
 * @author jasonpalmer
 */
class UserRepository extends EntityRepository {
    
    public function findUser($username){
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\User", $username);
    }
    
    
}
