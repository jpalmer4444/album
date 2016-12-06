<?php

namespace Ajax;

use Ajax\Controller\Sales\ItemsController;
use Ajax\Controller\Sales\SalesController;
use Ajax\Controller\Sales\UsersController;
use Ajax\Service\Sales\ItemsFilterTableArrayService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';
    
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e){
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getServiceConfig() {
        return [
            'factories' => [
                "ItemsFilterTableArrayService" => function($sm) {
                    return new ItemsFilterTableArrayService(
                            $sm
                    );
                },
            ],
        ];
    }
    
    public function getControllerConfig(){
        return [
            'factories' => [
                UsersController::class => function($container) {
                    return new UsersController(
                            $container
                    );
                },
                ItemsController::class => function($container) {
                    return new ItemsController(
                            $container
                    );
                },
                SalesController::class => function($container) {
                    return new SalesController(
                            $container
                    );
                },
            ],
        ];
    }
       
       
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
