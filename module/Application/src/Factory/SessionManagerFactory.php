<?php

namespace Application\Factory;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;
use Zend\View\Helper\Placeholder\Container;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class SessionManagerFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        $config = $sm->get('config');
        $session = $config['session'];
        $sessionConfig = null;
        if (isset($session['config'])) {
            $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
            $sessionConfig = new $class();
            $sessionConfig->setOptions($options);
        }
        $sessionStorage = null;
        if (isset($session['storage'])) {
            $class = $session['storage'];
            $sessionStorage = new $class();
        }
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $tableGateway = new TableGateway('session', $adapter);
        $saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
        $manager = new SessionManager();
        $manager->setSaveHandler($saveHandler);
        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $saveHandler);
        Container::setDefaultManager($sessionManager);
        return $sessionManager;
    }

}
