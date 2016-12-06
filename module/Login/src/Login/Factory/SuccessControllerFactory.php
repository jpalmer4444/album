<?php

namespace Login\Factory;

use Login\Controller\SuccessController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of SuccessControllerFactory
 *
 * @author jasonpalmer
 */
class SuccessControllerFactory {
    
    public function __invoke(ServiceLocatorInterface $container) {
        return new SuccessController(
                            $container->get('AuthService'),
                            $container->get('LoggingService')
                    );
    }
    
}
