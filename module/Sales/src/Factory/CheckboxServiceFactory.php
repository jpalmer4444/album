<?php

namespace Sales\Factory;

use Sales\Service\CheckboxService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of CheckboxServiceFactory
 *
 * @author jasonpalmer
 */
class CheckboxServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        $loggingService = $sm->get('LoggingService');
        $entityManager = $sm->get('FFMEntityManager');
        return new CheckboxService($loggingService, $entityManager);
    }

}
