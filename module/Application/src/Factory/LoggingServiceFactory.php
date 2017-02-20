<?php

namespace Application\Factory;

use Application\Service\LoggingService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoggingServiceFactory
 *
 * @author jasonpalmer
 */
class LoggingServiceFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new LoggingService($container->get('Log\\App'));
    }

}
