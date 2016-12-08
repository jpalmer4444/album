<?php

namespace Sales\Factory;

use Sales\Service\SalesFormService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of SalesFormServiceFactory
 *
 * @author jasonpalmer
 */
class SalesFormServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        $loggingService = $sm->get('LoggingService');
        return new SalesFormService($loggingService);
    }

}
