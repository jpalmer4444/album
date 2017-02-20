<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\Controller\UsersController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UsersControllerFactory
 *
 * @author jasonpalmer
 */
class UsersControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new UsersController(
                            $container
                    );
    }
    
}
