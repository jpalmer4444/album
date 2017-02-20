<?php

namespace Sales;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/*
 * 
 */

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';
    
    //vendor/fultonfishmarket/data/FultonFishMarket/DataAccess

    public function getAutoloaderConfig() {
        return array(
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
                "Sales\Controller\ItemsController" => "Sales\Factory\ItemsControllerFactory",
                "Sales\Controller\SalesController" => "Sales\Factory\SalesControllerFactory",
                "Sales\Controller\UsersController" => "Sales\Factory\UsersControllerFactory",
                "Sales\Controller\ApiController" => "Sales\Factory\ApiControllerFactory",
            ),
        ];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                'CheckboxService' => "Sales\Factory\CheckboxServiceFactory",
                'SalesFormService' => "Sales\Factory\SalesFormServiceFactory",
            ]
        ];
    }

    public function getFormElementConfig() {
        return array(
            'factories' => array(
                'RowPlusItemsPageForm' => "Sales\Factory\RowPlusItemsPageFormFactory",
            ),
        );
    }

}
