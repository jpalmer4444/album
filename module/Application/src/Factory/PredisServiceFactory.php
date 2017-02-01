<?php

namespace Application\Factory;

use Application\Service\PredisService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoggingServiceFactory
 *
 * @author jasonpalmer
 */
class PredisServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        return new PredisService($sm);
    }

}
