<?php

namespace Command;

use Application\Utility\Strings;
use Command\Controller\Reporting\PriceOverrideController;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface{

    const VERSION = '3.0.2dev';
    
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig() {
        return [];
    }
    
    public function getControllerConfig(){
        return [
            'factories' => [
                PriceOverrideController::class => function($container) {
                    return new PriceOverrideController(
                            $container->get('LoggingService'),
                            $container->get(Strings::FFM_ENTITY_MANAGER)->getEntityManager(),
                            $container->get(Strings::CONFIG)[Strings::PRICING_CONFIG]
                    );
                },
            ],
        ];
    }
       
    public function getConsoleUsage(Console $console)
    {
        return [
            // Describe available commands
            'price override [--verbose|-v]' => 'Show Pricing Reports For Date Range.',

            // Describe expected parameters
            [ '--verbose|-v', '(optional) turn on verbose mode'        ],
        ];
    } 
    
    public function getAutoloaderConfig()
    {
        return array(
            Strings::CLASS_MAP_AUTO_LOADER => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            Strings::STANDARD_AUTO_LOADER => array(
                Strings::NAMESPACES => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
