<?php

namespace Sales\Factory;

use Sales\Controller\UsersController;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of UsersControllerFactory
 *
 * @author jasonpalmer
 */
class UsersControllerFactory {
    
    public function __invoke(ServiceManager $container) {
        return new UsersController(
                            $container
                    );
    }
    
}
