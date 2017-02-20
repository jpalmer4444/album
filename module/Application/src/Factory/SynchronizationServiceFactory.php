<?php

namespace Application\Factory;

use Application\Service\SynchronizationService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class SynchronizationServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        //need to rewrite this for testing! Which means injecting objects!
        return new SynchronizationService($container);
    }

}
