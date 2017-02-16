<?php

namespace Application\Factory;

use Application\Service\SessionService;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\SessionManager;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class SessionServiceFactory {

    public function __invoke(ServiceManager $sm) {
        $sessionManager = $sm->get(SessionManager::class);
        $userrepository = $sm->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $userrolexrefrepository = $sm->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserRoleXref');
        $request = $sm->get('Request');
        $response = $sm->get('Response');
        return new SessionService($userrepository, $userrolexrefrepository, $sessionManager, $request, $response);
    }

}
