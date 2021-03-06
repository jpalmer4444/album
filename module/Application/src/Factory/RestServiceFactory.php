<?php

namespace Application\Factory;

use Application\Service\RestService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class RestServiceFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new RestService($container);
    }

}
