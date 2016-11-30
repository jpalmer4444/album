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
use Application\Factory\SessionManagerFactory;
use Application\Service\LoggingService;
use Application\Service\ReportService;
use Application\Service\RestService;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
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
                'LoggingService' => LoggingServiceFactory::class,
                'RestService' => RestServiceFactory::class,
                'ReportService' => ReportServiceFactory::class,
                'FFMEntityManager' => FFMEntityManagerServiceFactory::class,
                'SessionManager' => SessionManagerFactory::class,
            ),
        );
    }

    /**
     * Adds global method available in layout
     */
    public function onBootstrap($e) {
        $sm = $e->getApplication()->getServiceManager();

        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);

        if ($matchedRoute) {
            $params = $matchedRoute->getParams();

            $controller = $params['controller'];

            //only needed when this is not an Ajax request and not security related.
            if (isset($params['action'])) {
                $action = $params['action'];

                $module_array = explode('\\', $controller);
                $module = array_pop($module_array);

                $route = $matchedRoute->getMatchedRouteName();

                $e->getViewModel()->setVariables(
                        array(
                            'CURRENT_MODULE_NAME' => $module,
                            'CURRENT_CONTROLLER_NAME' => $controller,
                            'CURRENT_ACTION_NAME' => $action,
                            'CURRENT_ROUTE_NAME' => $route,
                        )
                );
            }
        }
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'formlabel' => 'Application\ViewHelper\RequiredMarkInFormLabel',
            ),
        );
    }

}
