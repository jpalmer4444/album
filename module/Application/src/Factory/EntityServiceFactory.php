<?php

namespace Application\Factory;

use Application\Service\EntityService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class EntityServiceFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new EntityService($container->get('Doctrine\ORM\EntityManager'));
    }
    
}
