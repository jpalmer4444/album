<?php

namespace Application\Factory;

use Application\Service\FFMEntityManagerService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class FFMEntityManagerServiceFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new FFMEntityManagerService($container->get('Doctrine\ORM\EntityManager'));
    }
    
}
