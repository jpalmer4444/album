<?php

namespace Command\Query;

/**
 *
 * @author jasonpalmer
 */
interface ORMQuery {
    
    const QUERY_ALL_PRICING_OVERRIDES = "SELECT override FROM DataAccess\FFM\Entity\PricingOverrideReport override WHERE "
                . "override._created BETWEEN :from AND :to ORDER BY override._salesperson DESC";
    
}
