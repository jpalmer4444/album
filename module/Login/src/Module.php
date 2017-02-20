<?php

namespace Login;

use Application\Utility\Strings;
use Login\Factory\LoginControllerFactory;
use Login\Factory\SuccessControllerFactory;
use Login\Model\MyAuthStorage;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';
    
    public function getAutoloaderConfig() {
        return array(
            Strings::STANDARD_AUTO_LOADER => array(
                Strings::NAMESPACES => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig() {

        return [
            Strings::FACTORIES => [
                Strings::LOGIN_CONTROLLER => LoginControllerFactory::class,
                Strings::SUCCESS_CONTROLLER => SuccessControllerFactory::class,
            ],
        ];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                Strings::AUTH_SERVICE => function($sm) {
                    //password hashed with bcrypt
                    $credentialValidationCallback = function($dbCredential, $requestCredential) {
                                return (new Bcrypt())->verify($requestCredential, $dbCredential);
                            };
                    $dbAdapter = $sm->get(Strings::ZEND_DB_ADAPTER);
                    $authAdapter = new AuthAdapter($dbAdapter, 'users', 'username', 'password', $credentialValidationCallback);
                    $authService = new AuthenticationService();
                    $authService->setAdapter($authAdapter);
                    //do not override storage for AuthenticationService!
                    return $authService;
                },
            ),
        );
    }

}
