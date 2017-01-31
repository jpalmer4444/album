<?php

namespace Application\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class SessionManagerFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        $sessionConfig = $sm->get('PredisService');
        $sessionManager = new SessionManager($sessionConfig);
        Container::setDefaultManager($sessionManager);
        return $sessionManager;
    }

}
