<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\IndexController;
use Application\Factory\FFMEntityManagerServiceFactory;
use Application\Factory\LoggingServiceFactory;
use Application\Factory\ReportServiceFactory;
use Application\Factory\RestServiceFactory;
use Application\Factory\SessionServiceFactory;
use Application\Utility\Strings;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

class Module implements ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface {

    const VERSION = '3.0.2dev';

    public function getControllerConfig() {
        return [
            Strings::CONTROLLERS => [
                Strings::FACTORIES => [
                    IndexController::class => InvokableFactory::class,
                ],
        ]];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            Strings::CLASS_MAP_AUTO_LOADER => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            Strings::STANDARD_AUTO_LOADER => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            Strings::FACTORIES => array(
                'LoggingService' => LoggingServiceFactory::class,
                Strings::REST_SERVICE => RestServiceFactory::class,
                Strings::REPORT_SERVICE => ReportServiceFactory::class,
                Strings::FFM_ENTITY_MANAGER => FFMEntityManagerServiceFactory::class,
                'SessionService' => SessionServiceFactory::class,
            ),
        );
    }

    /**
     * Adds global method available in layout
     */
    public function onBootstrap($e) {

        $sm = $e->getApplication()->getServiceManager();

        $router = $sm->get(Strings::ROUTER);
        $request = $sm->get(Strings::REQUEST);
        $matchedRoute = $router->match($request);

        if ($matchedRoute) {
            $params = $matchedRoute->getParams();

            $controller = $params[Strings::CONTROLLER];

            //only needed when this is not an Ajax request and not security related.
            if (isset($params[Strings::ACTION])) {
                $action = $params[Strings::ACTION];

                $module_array = explode('\\', $controller);
                $module = array_pop($module_array);

                $route = $matchedRoute->getMatchedRouteName();
                
                $sessionService = $sm->get('SessionService');
                
                $e->getViewModel()->setVariables(
                        array(
                            'CURRENT_MODULE_NAME' => $module,
                            'CURRENT_CONTROLLER_NAME' => $controller,
                            'CURRENT_ACTION_NAME' => $action,
                            'CURRENT_ROUTE_NAME' => $route,
                            'FFM_SESSION' => [
                                'user' => $sessionService->getUser(),
                                'roles' => $sessionService->getRoles(),
                                'salespersoninplay' => $sessionService->getSalespersonInPlay()
                            ]
                        )
                );
            }
        }
    }

    public function bootstrapSession($e) {
        $session = $e->getApplication()
                ->getServiceManager()
                ->get(SessionManager::class);
        $session->start();
        $container = new Container('initialized');
        if (isset($container->init)) {
            return;
        }
        $serviceManager = $e->getApplication()->getServiceManager();
        $request = $serviceManager->get('Request');
        $session->setStorage(new SessionArrayStorage());
        //$session->regenerateId();
        $container->init = 1;
        $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
        $config = $serviceManager->get('Config');
        if (!isset($config['session'])) {
            return;
        }
        $sessionConfig = $config['session'];
        if (!isset($sessionConfig['validators'])) {
            return;
        }
        $chain = $session->getValidatorChain();
        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
                case HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                case RemoteAddr::class:
                    $validator = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }
            $chain->attach('session.validate', array($validator, 'isValid'));
        }
    }

    public function getViewHelperConfig() {
        return array(
            Strings::INVOKABLES => array(
                Strings::FORM_LABEL => Strings::REQUIRED_MARK_IN_FORM_LABEL,
            ),
        );
    }

}
