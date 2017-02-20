<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\Controller\SalesController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of SalesControllerFactory
 *
 * @author jasonpalmer
 */
class SalesControllerFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new SalesController(
                $container, 
                $container->get('FFMEntityManager'), 
                $container->get('FormElementManager'), 
                $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User')
        );
    }

}
