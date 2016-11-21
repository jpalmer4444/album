<?php

namespace Application\Service;

use DateTime;

/**
 * Description of ReportService
 *
 * @author jasonpalmer
 */
class ReportService implements ReportServiceInterface{
    
    protected $pricingoverridereportrepository;
    
    public function __construct($container) {
        $this->pricingoverridereportrepository = $container->get('FFMEntityManager')->
                getEntityManager()->getRepository('DataAccess\FFM\Entity\PricingOverrideReport');
    }
    
    public function pricingOverrideReport(DateTime $from, DateTime $to){
        
        
        
    }
    
}
