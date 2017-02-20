<?php

namespace Application\Factory;

use Application\Service\PredisService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoggingServiceFactory
 *
 * @author jasonpalmer
 */
class PredisServiceFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, mixed $options = null) {
        return new PredisService($container);
    }

}
