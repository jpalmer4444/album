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
        $userrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $userrolexrefrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserRoleXref');
        $request = $container->get('Request');
        $response = $container->get('Response');
        $config = $container->get('Config');
        $cookieLifetime = 86400;
        if (array_key_exists('session_config', $config) &&
                array_key_exists('cookie_lifetime', $config['session_config'])) {
            $session_config = $config['session_config'];
            $cookieLifetime = $session_config['cookie_lifetime'];
        }

        return new SessionService($userrepository, $userrolexrefrepository, $sessionManager, $request, $response, $cookieLifetime);
    }

}
