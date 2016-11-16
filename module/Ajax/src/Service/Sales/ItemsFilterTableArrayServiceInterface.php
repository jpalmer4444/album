<?php

namespace Ajax\Service\Sales;

/**
 *
 * @author jasonpalmer
 */
interface ItemsFilterTableArrayServiceInterface {
    
    const QUERY_PAGES = "SELECT rowPlus FROM DataAccess\FFM\Entity\RowPlusItemsPage rowPlus WHERE "
                . "rowPlus._created >= :created AND "
                . "rowPlus._active = 1 AND "
                . "rowPlus._customerid = :customerid AND rowPlus._salesperson = :salesperson "
                . "GROUP BY rowPlus.product ORDER BY rowPlus._created DESC";
    
    const QUERY_OVERRIDES = "SELECT override FROM DataAccess\FFM\Entity\ItemPriceOverride override WHERE "
                . "override._created >= :created AND "
                . "override._active = 1 AND "
                . "override._customerid = :customerid AND override._salesperson = :salesperson "
                . "GROUP BY override.sku ORDER BY override._created DESC";
    
    public function _filter($restcallitems, $customerid);
}
