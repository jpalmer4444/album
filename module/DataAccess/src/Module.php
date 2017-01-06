<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataAccess;

use Application\Utility\Strings;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';
    
    public function getAutoloaderConfig() {
        return array(
            Strings::CLASS_MAP_AUTO_LOADER => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            Strings::STANDARD_AUTO_LOADER => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig() {
        
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        
    }

}
