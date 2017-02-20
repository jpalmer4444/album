<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use Application\Utility\Logger;
use Exception;

/**
 * Description of ItemPriceOverrideRepository
 *
 * @author jasonpalmer
 */
class ItemPriceOverrideRepositoryImpl extends FFMRepository {
    
    const QUERY_BY_CUSTOMERID_AND_SALESPERSON_AND_PRODUCT = 'SELECT ipo FROM \DataAccess\FFM\Entity\ItemPriceOverride ipo WHERE ipo._salesperson= :username AND ipo._customerid = :customerid AND ipo.product = :productid AND ipo._active = 1 ORDER BY ipo._created DESC';
    
    public function findItemPriceOverride($salespersonusername, $productid, $customerid) {
        $query = $this->getEntityManager()->createQuery(ItemPriceOverrideRepositoryImpl::QUERY_BY_CUSTOMERID_AND_SALESPERSON_AND_PRODUCT);
        $query->setParameter("username", $salespersonusername);
        $query->setParameter("customerid", $customerid);
        $query->setParameter("productid", $productid);
        try {
            $arr = $query->getResult();
            if (!empty(count($arr))) {
                return $arr[0];
            }
        } catch (Exception $exc) {
            Logger::info("ItemPriceOverrideRepositoryImpl", __LINE__, $exc->getTraceAsString());
        }
    }
    
    
    
}
