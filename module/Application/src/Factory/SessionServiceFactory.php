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
        $sessionId = $sm->get(SessionManager::class)->getId();
        $userrepository = $sm->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $userrolexrefrepository = $sm->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserRoleXref');
        return new SessionService($userrepository, $userrolexrefrepository, $sessionId);
    }

}
