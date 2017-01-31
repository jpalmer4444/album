<?php

namespace Login\Factory;

use Application\Utility\Strings;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class AuthServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        
        $credentialValidationCallback = function($dbCredential, $requestCredential) {
            return (new Bcrypt())->verify($requestCredential, $dbCredential);
        };

        $dbAdapter = $sm->get(Strings::ZEND_DB_ADAPTER);
        $authAdapter = new AuthAdapter($dbAdapter, 'users', 'username', 'password', $credentialValidationCallback);
        $authService = new AuthenticationService();
        $authService->setAdapter($authAdapter);
        $predisService = new \Application\Service\PredisService($sm);
        //$authService->setStorage($predisService);

        return $authService;
    }

}
