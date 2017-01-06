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
use Application\Utility\Strings;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

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

    public function getAutoloaderConfig()
     {
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
                Strings::SESSION_MANAGER => SessionManagerFactory::class,
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
            Strings::INVOKABLES => array(
                Strings::FORM_LABEL => Strings::REQUIRED_MARK_IN_FORM_LABEL,
            ),
        );
    }

}
