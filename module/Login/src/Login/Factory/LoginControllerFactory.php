<?php

namespace Login\Factory;

use Login\Controller\LoginController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class LoginControllerFactory {
    
    public function __invoke(ServiceLocatorInterface $container) {
        return new LoginController(
                            $container->get('AuthService'), 
                            $container->get('PredisService'),
                            $container->get('LoggingService'),
                            $container->get('FFMEntityManager'),
                $container
                    );
    }
    
}
