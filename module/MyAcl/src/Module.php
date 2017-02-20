<?php

namespace MyAcl; // added for module specific layouts. ericp
// added for Acl  ###################################


use Application\Utility\Strings;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

// end: added for Acl   ###################################
//class Module
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';
    
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    // added for Acl   ###################################
    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
        //you can attach other function need here...
    }

    public function loadConfiguration(MvcEvent $e) {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $sm->get(Strings::ROUTER);
        $request = $sm->get(Strings::REQUEST);

        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            $sharedManager->attach(Strings::ABSTRACT_ACTION_CONTROLLER, Strings::DISPATCH, function($e) use ($sm) {
                $sm->get('ControllerPluginManager')->get('MyAclPlugin')
                        ->doAuthorization($e); //pass to the plugin...    
            }, 2
            );
        }
    }
    
    
        // end: added for Acl   ###################################
       
        /*
         *  // added init() func for module specific layouts. ericp
         * <a href="http://blog.evan.pro/module-specific-layouts-in-zend-framework-2
">http://blog.evan.pro/module-specific-layouts-in-zend-framework-2
</a>     */
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, Strings::DISPATCH, function($e) {
            // This event will only be fired when an ActionController under the MyModule namespace is dispatched.
            $controller = $e->getTarget();
            //$controller->layout('layout/zfcommons'); // points to module/Album/view/layout/album.phtml
        }, 100);
    }
    
    public function getServiceConfig() {
        return [];
    }
       
       
    public function getAutoloaderConfig()
    {
        return array(
            Strings::STANDARD_AUTO_LOADER => array(
                Strings::NAMESPACES => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
