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
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Container;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface  {

    const VERSION = '3.0.2dev';
    const BYSKU_URL = "https://svc.ffmalpha.com/bySKU.php";
    const BYSKU_METHOD = "GET";

    public function getControllerConfig() {
        return [
            'controllers' => [
                'factories' => [
                    IndexController::class => InvokableFactory::class,
                ],
        ]];
    }

    public function getConsoleBanner(\Zend\Console\Adapter\AdapterInterface $console)
    {
        return 'Application Module V: ' . Module::VERSION;
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
                'FFMEntityManager' => function($sm) {
                    return new Service\FFMEntityManagerService($sm->get('Doctrine\ORM\EntityManager'));
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

    public function getConsoleUsage(\Zend\Console\Adapter\AdapterInterface $console) {
        return array(
            // Describe available commands
            'user resetpassword [--verbose|-v] EMAIL'    => 'Reset password for a user',

            // Describe expected parameters
            array( 'EMAIL',            'Email of the user for a password reset' ),
            array( '--verbose|-v',     '(optional) turn on verbose mode'        ),
        );
    }
    
    /**
     * Adds global method available in layout
     */
    
    

}
