<?php

namespace Application\Service;

use DateTime;

/**
 *
 * @author jasonpalmer
 */
interface ReportServiceInterface {
    
    public function pricingOverrideReport(DateTime $from, DateTime $to);
    
}
