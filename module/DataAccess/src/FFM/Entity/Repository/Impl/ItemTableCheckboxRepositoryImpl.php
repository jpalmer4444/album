<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use Exception;

/**
 * Description of ItemTableCheckboxRepository
 *
 * @author jasonpalmer
 */
class ItemTableCheckboxRepositoryImpl extends FFMRepository {

    const QUERY_IDS_BY_CUSTOMERID_AND_SALESPERSON = 'SELECT itemTableCheckbox FROM \DataAccess\FFM\Entity\ItemTableCheckbox'
            . ' itemTableCheckbox WHERE itemTableCheckbox._salesperson = :username AND itemTableCheckbox._customerid ='
            . ' :customerid';
    const QUERY_CHECKBOX_BY_CUSTOMERID_AND_SALESPERSON_AND_ID = 'SELECT itemTableCheckbox FROM \DataAccess\FFM\Entity'
            . '\ItemTableCheckbox itemTableCheckbox WHERE itemTableCheckbox._salesperson = :username AND '
            . 'itemTableCheckbox._customerid = :customerid AND (itemTableCheckbox.product = :product OR itemTableCheckbox.rowPlusItemsPage = :rowPlusItemsPage)';

    public function findCheckbox($rowplusitemspage, $product, $customerid, $salespersonusername) {
        $query = $this->getEntityManager()->createQuery(ItemTableCheckboxRepositoryImpl::QUERY_CHECKBOX_BY_CUSTOMERID_AND_SALESPERSON_AND_ID);
        $query->setParameter("username", $salespersonusername);
        $query->setParameter("customerid", $customerid);
        $query->setParameter("product", $product);
        $query->setParameter("rowPlusItemsPage", $rowplusitemspage);
        try {
            $arr = $query->getResult();
            if(!empty(count($arr))){
                return $arr[0];
            }
        } catch (Exception $exc) {
            $this->logger->info($exc->getTraceAsString());
        }
    }

    public function getAllIDsByCustomerIdAndSalesperson($username, $customerid) {
        $query = $this->getEntityManager()->createQuery(ItemTableCheckboxRepositoryImpl::QUERY_IDS_BY_CUSTOMERID_AND_SALESPERSON);
        $query->setParameter("username", $username);
        $query->setParameter("customerid", $customerid);
        return $query->getResult();
    }

    public function removeCheckbox($id, $customerid, $salespersonusername) {
        if (strpos($id, 'P') !== false){
            $record = $this->findCheckbox(null, substr($id, 1), $customerid, $salespersonusername);
        }else{
            $record = $this->findCheckbox(substr($id, 1), null, $customerid, $salespersonusername);
        }
        //error_log("Record: " . $record, 0);
        $record->setChecked(false);
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();
    }

}
