<?php

namespace Sales\Service;

use DataAccess\FFM\Entity\PricingOverrideReport;

/**
 *
 * @author jasonpalmer
 */
interface PricingReportPersistenceServiceInterface {
    
    public function persist(PricingOverrideReport $pricingOverrideReport);
    
}
