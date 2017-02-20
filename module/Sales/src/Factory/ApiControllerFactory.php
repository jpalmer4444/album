<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\Controller\ApiController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ApiControllerFactory
 *
 * @author jasonpalmer
 */
class ApiControllerFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new ApiController($container);
    }

}
