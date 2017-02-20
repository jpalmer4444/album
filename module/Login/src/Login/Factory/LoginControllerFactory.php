<?php

namespace Login\Factory;

use Interop\Container\ContainerInterface;
use Login\Controller\LoginController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class LoginControllerFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $authService = $container->get('AuthService');
        $sessionService = $container->get('SessionService');
        $sessionManager = $container->get(SessionManager::class);
        $loggingService = $container->get('LoggingService');
        $entityManager = $container->get('FFMEntityManager');
        $userrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $userrolexrefrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserRoleXref');
        return new LoginController(
                $authService, $loggingService, $entityManager, $sessionService, $sessionManager, $userrepository, $userrolexrefrepository
        );
    }

}
