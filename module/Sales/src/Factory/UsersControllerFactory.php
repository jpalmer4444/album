<?php

namespace Sales\Factory;

use Application\Utility\Strings;
use Interop\Container\ContainerInterface;
use Sales\Controller\UsersController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UsersControllerFactory
 *
 * @author jasonpalmer
 */
class UsersControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $usersController = new UsersController(
                $container
        );

        //set BaseController properties.
        $usersController->setDbAdapter($container->get(Strings::ZEND_DB_ADAPTER));
        $usersController->setAuthService($container->get(Strings::AUTH_SERVICE));
        $usersController->setConfig($container->get('Config'));

        //return the controller
        return $usersController;
    }

}
