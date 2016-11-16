<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ItemTableCheckboxRepository
 *
 * @author jasonpalmer
 */
class ItemTableCheckboxRepository extends EntityRepository {

    const QUERY_SKUS_BY_CUSTOMERID_AND_SALESPERSON = 'SELECT itemTableCheckbox FROM \DataAccess\FFM\Entity\ItemTableCheckbox'
            . ' itemTableCheckbox WHERE itemTableCheckbox._salesperson = :username AND itemTableCheckbox._customerid ='
            . ' :customerid';
    const QUERY_CHECKBOX_BY_CUSTOMERID_AND_SALESPERSON_AND_SKU = 'SELECT itemTableCheckbox FROM \DataAccess\FFM\Entity'
            . '\ItemTableCheckbox itemTableCheckbox WHERE itemTableCheckbox._salesperson = :username AND '
            . 'itemTableCheckbox._customerid = :customerid AND itemTableCheckbox.sku = :sku';

    public function findCheckbox($sku, $customerid, $salespersonusername) {
        $query = $this->getEntityManager()->createQuery(ItemTableCheckboxRepository::QUERY_CHECKBOX_BY_CUSTOMERID_AND_SALESPERSON_AND_SKU);
        $query->setParameter("username", $salespersonusername);
        $query->setParameter("customerid", $customerid);
        $query->setParameter("sku", $sku);
        try {
            return $query->getResult();
        } catch (Exception $exc) {
            $this->logger->info($exc->getTraceAsString());
        }
    }

    public function getAllSkusByCustomerIdAndSalesperson($username, $customerid) {
        $query = $this->getEntityManager()->createQuery(ItemTableCheckboxRepository::QUERY_SKUS_BY_CUSTOMERID_AND_SALESPERSON);
        $query->setParameter("username", $username);
        $query->setParameter("customerid", $customerid);
        return $query->getResult();
    }

    public function removeCheckbox($sku, $customerid, $salespersonusername) {
        $record = $this->findCheckbox($sku, $customerid, $salespersonusername);
        $record->setChecked(false);
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();
    }

}
