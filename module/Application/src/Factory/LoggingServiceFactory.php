<?php

namespace Application\Factory;

use Application\Service\LoggingService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoggingServiceFactory
 *
 * @author jasonpalmer
 */
class LoggingServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        return new LoggingService($sm->get('Log\\App'));
    }

}
