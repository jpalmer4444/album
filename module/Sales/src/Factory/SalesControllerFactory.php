<?php

namespace Sales\Factory;

use Sales\Controller\SalesController;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of SalesControllerFactory
 *
 * @author jasonpalmer
 */
class SalesControllerFactory {

    public function __invoke(ServiceManager $container) {
        return new SalesController(
                $container, 
                $container->get('FFMEntityManager'), 
                $container->get('FormElementManager'), 
                $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User')
        );
    }

}
