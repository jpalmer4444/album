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
        $authService = $container->get('AuthService');
        $predisService = $container->get('PredisService');
        $loggingService = $container->get('LoggingService');
        $entityManager = $container->get('FFMEntityManager');
        return new LoginController(
                $authService, $predisService, $loggingService, $entityManager
        );
    }

}
