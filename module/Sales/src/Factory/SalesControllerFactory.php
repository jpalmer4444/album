<?php

namespace Sales\Factory;

use Sales\Controller\SalesController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of SalesControllerFactory
 *
 * @author jasonpalmer
 */
class SalesControllerFactory {

    public function __invoke(ServiceLocatorInterface $container) {
        return new SalesController(
                $container, 
                $container->get('FFMEntityManager'), 
                $container->get('FormElementManager'), 
                $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User')
        );
    }

}