<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\IndexController;
use Application\Service\LoggingService;
use Application\Service\RestService;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Container;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;

class Module implements ConfigProviderInterface, ServiceProviderInterface {

    const VERSION = '3.0.2dev';

    public function getControllerConfig() {
        return [
            'controllers' => [
                'factories' => [
                    IndexController::class => InvokableFactory::class,
                ],
        ]];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'LoggingService' => function($sm) {
                    return new LoggingService("Logging initialized from Application module.");
                },
                'RestService' => function($sm) {
                    return new RestService($sm->get('LoggingService'));
                },
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
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

                        /* $sessionSaveHandler = null;
                          if (isset($session['save_handler'])) {
                          // class should be fetched from service manager since it will require constructor arguments
                          $sessionSaveHandler = $sm->get($session['save_handler']);
                          } */
                        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $tableGateway = new TableGateway('session', $adapter);
                        $saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
                        $manager = new SessionManager();
                        $manager->setSaveHandler($saveHandler);

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $saveHandler);
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
            ),
        );
    }

}
