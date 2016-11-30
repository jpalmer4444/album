<?php

namespace Application\Factory;

use Application\Service\FFMEntityManagerService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class FFMEntityManagerServiceFactory {
    
    public function __invoke(ServiceLocatorInterface $sm) {
        return new FFMEntityManagerService($sm->get('Doctrine\ORM\EntityManager'));
    }
    
}
