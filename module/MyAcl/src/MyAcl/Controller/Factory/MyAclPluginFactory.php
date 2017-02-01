<?php

namespace MyAcl\Controller\Factory;

use MyAcl\Controller\Plugin\MyAclPlugin;
use Zend\ServiceManager\ServiceLocatorInterface;


/*
 * Factory for creating MyAclPlugin
 */

class MyAclPluginFactory 
{
    public function __invoke(ServiceLocatorInterface $container) {
        $loggingService = $container->get('LoggingService');
                    $authService = $container->get('AuthService');
                    $predisService = $container->get('PredisService');
                    return new MyAclPlugin($loggingService, $authService, $predisService);
    }

}
