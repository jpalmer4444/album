<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use Doctrine\ORM\EntityRepository;

/**
 * Description of Repository
 *
 * @author jasonpalmer
 */
abstract class FFMRepository extends EntityRepository {
    
    public function persistAndFlush($record){
        $this->getEntityManager()->persist($record);
        $this->flush();
    }
    
    public function persist($record){
        $this->getEntityManager()->persist($record);
    }
    
    public function flush(){
        $this->getEntityManager()->flush();
    }
    
    public function createQueryGetResults($query, $unique){
        $q = $this->getEntityManager()->createQuery($query);
        return $unique ? $q->getSingleResult() : $q->getResult();
    }
    
    public function merge($record) {
        $this->getEntityManager()->merge($record);
    }
    
    public function mergeAndFlush($record) {
        $this->getEntityManager()->merge($record);
        $this->flush();
    }
    
}
