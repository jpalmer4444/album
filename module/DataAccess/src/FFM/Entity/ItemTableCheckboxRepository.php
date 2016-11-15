<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ItemTableCheckboxRepository
 *
 * @author jasonpalmer
 */
class ItemTableCheckboxRepository extends EntityRepository {
    
    const QUERY_SKUS_BY_CUSTOMERID_AND_SALESPERSON = 'SELECT itemTableCheckbox FROM \DataAccess\FFM\Entity\ItemTableCheckbox itemTableCheckbox WHERE '
            . 'itemTableCheckbox._salesperson = :username AND itemTableCheckbox._customerid = :customerid';
    
    public function findCheckbox($sku, $customerid, $salespersonusername){
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\ItemTableCheckbox", array("sku" => $sku, "_customerid" => $customerid, "_salesperson" => $salespersonusername));
    }

    public function getAllSkusByCustomerIdAndSalesperson($username, $customerid) {
        $query = $this->getEntityManager()->createQuery(ItemTableCheckboxRepository::QUERY_SKUS_BY_CUSTOMERID_AND_SALESPERSON);
        $query->setParameter("username", $username);
        $query->setParameter("customerid", $customerid);
        return $query->getResult();
    }

}
