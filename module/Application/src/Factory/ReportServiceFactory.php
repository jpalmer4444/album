<?php

namespace Application\Factory;

use Application\Service\ReportService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class ReportServiceFactory {
    
    public function __invoke(ServiceLocatorInterface $sm) {
        return new ReportService($sm);
    }
    
}
