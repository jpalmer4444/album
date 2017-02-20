<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataAccess;

use Application\Utility\Logger;
use Application\Utility\Strings;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface {

    const VERSION = '3.0.2dev';

    public function getAutoloaderConfig() {
        return array(
            Strings::STANDARD_AUTO_LOADER => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig() {
        
    }

    /*
     * Learn ZF2 Learn By Example page 35 
     * onBootstrap(MVCEvent $e) called after
     * Preparation phase has completed boostrap 
     * is called. 
     */
    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));
    }

    /**
     * Application scoped Event Handler
     * that is used to print relevant 
     * error/exception about errors within 
     * the Request/Response phases.
     * 
     * @param MvcEvent $event
     */
    public function handleError(MvcEvent $event) {
        $controller = $event->getController();
        $error = $event->getParam('error');
        $exception = $event->getParam('exception');
        $message = sprintf(' Error dispatching controller "%s". Error was: "%s"', $controller, $error);
        if ($exception instanceof \Exception) {
            $message .= ', Exception(' . $exception->getMessage() . '): ' . $exception->getTraceAsString();
        }
        Logger::info("DataAccess\Module", __LINE__, $message);
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        
    }

}
