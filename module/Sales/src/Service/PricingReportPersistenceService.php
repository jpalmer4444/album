<?php

namespace Sales\Service;

use Application\Service\FFMEntityManagerServiceInterface;
use Application\Service\LoggingServiceInterface;

/**
 * Description of PricingReportPersistenceService
 *
 * @author jasonpalmer
 */
class PricingReportPersistenceService implements PricingReportPersistenceServiceInterface {
    
    protected $logger;
    
    protected $entityManager;
    
    public function __construct(FFMEntityManagerServiceInterface $entityManager, LoggingServiceInterface $logger) {
        $this->logger = $logger;
        $this->entityManager = $entityManager->getEntityManager();
    }
    
    public function persist($pricingOverrideReports) {
        
    }

}
