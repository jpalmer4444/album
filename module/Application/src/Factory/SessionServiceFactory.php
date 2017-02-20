<?php

namespace Application\Factory;

use Application\Service\SessionService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class SessionServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $sessionManager = $container->get(SessionManager::class);
        $userrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $userrolexrefrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserRoleXref');
        $request = $container->get('Request');
        $response = $container->get('Response');
        $config = $container->get('Config');
        $cookieService = $container->get('CookieService');

        return new SessionService($userrepository, $userrolexrefrepository, $sessionManager, $request, $response, $cookieService);
    }

}
