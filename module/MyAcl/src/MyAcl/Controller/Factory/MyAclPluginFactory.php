<?php

namespace MyAcl\Controller\Factory;

use Interop\Container\ContainerInterface;
use MyAcl\Controller\Plugin\MyAclPlugin;
use Zend\ServiceManager\Factory\FactoryInterface;

/*
 * Factory for creating MyAclPlugin
 */

class MyAclPluginFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $loggingService = $container->get('LoggingService');
        $sessionService = $container->get('SessionService');
        $config = $container->get('Config');
        $cookieService = $container->get('CookieService');
        
        return new MyAclPlugin($loggingService, $sessionService, $cookieService);
    }

}
