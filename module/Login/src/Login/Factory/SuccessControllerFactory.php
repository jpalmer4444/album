<?php

namespace Login\Factory;

use Interop\Container\ContainerInterface;
use Login\Controller\SuccessController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of SuccessControllerFactory
 *
 * @author jasonpalmer
 */
class SuccessControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new SuccessController(
                            $container->get('AuthService'),
                            $container->get('LoggingService')
                    );
    }
    
}
