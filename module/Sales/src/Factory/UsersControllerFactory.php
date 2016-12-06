<?php

namespace Sales\Factory;

use Sales\Controller\UsersController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UsersControllerFactory
 *
 * @author jasonpalmer
 */
class UsersControllerFactory {
    
    public function __invoke(ServiceLocatorInterface $container) {
        return new UsersController(
                            $container
                    );
    }
    
}
