<?php

namespace Application\Factory;

use Application\Service\RestService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class RestServiceFactory {

    public function __invoke(ServiceLocatorInterface $sm) {
        return new RestService($sm);
    }

}
