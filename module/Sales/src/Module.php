<?php

namespace Sales;

use Sales\Controller\ItemsController;
use Sales\Controller\SalesController;
use Sales\Controller\UsersController;
use Sales\Factory\CheckboxServiceFactory;
use Sales\Factory\ItemsControllerFactory;
use Sales\Factory\RowPlusItemsPageFormFactory;
use Sales\Factory\SalesControllerFactory;
use Sales\Factory\SalesFormServiceFactory;
use Sales\Factory\UsersControllerFactory;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/*
 * 
 */

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';

    public function getAutoloaderConfig() {
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

    public function getControllerConfig() {
        return [
            'factories' => array(
                ItemsController::class => ItemsControllerFactory::class,
                SalesController::class => SalesControllerFactory::class,
                UsersController::class => UsersControllerFactory::class,
            ),
        ];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                'CheckboxService' => CheckboxServiceFactory::class,
                'SalesFormService' => SalesFormServiceFactory::class,
            ]
        ];
    }

    public function getFormElementConfig() {
        return array(
            'factories' => array(
                'RowPlusItemsPageForm' => RowPlusItemsPageFormFactory::class,
            ),
        );
    }

}
