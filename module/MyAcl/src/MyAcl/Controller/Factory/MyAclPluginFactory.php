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
                    $sessionService = $container->get('SessionService');
                    return new MyAclPlugin($loggingService, $sessionService);
    }

}
