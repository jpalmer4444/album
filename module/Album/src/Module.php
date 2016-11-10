<?php

namespace Album;

use Album\Controller\AlbumController;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
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
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }
     
     public function getControllerConfig()
    {
        return [
            'factories' => [
                AlbumController::class => function($container) {
                    return new AlbumController(
                        $container->get(AlbumTable::class)
                    );
                },
            ],
        ];
    }
    
    protected function _initAutoloader()
    {
        $loader = function($className) {
            $className = str_replace('\\', '_', $className);
            Zend_Loader_Autoloader::autoload($className);
        };

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->pushAutoloader($loader, 'Application\\');
    }

}
