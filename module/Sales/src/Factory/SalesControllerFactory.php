<?php

namespace Sales\Factory;

use Application\Utility\Strings;
use Interop\Container\ContainerInterface;
use Sales\Controller\SalesController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of SalesControllerFactory
 *
 * @author jasonpalmer
 */
class SalesControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $salesController = new SalesController(
                $container, 
                $container->get('EntityService'), 
                $container->get('FormElementManager'), 
                $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User')
        );

        //set BaseController properties.
        $salesController->setDbAdapter($container->get(Strings::ZEND_DB_ADAPTER));
        $salesController->setAuthService($container->get(Strings::AUTH_SERVICE));
        $salesController->setConfig($container->get('Config'));

        //return the controller
        return $salesController;
    }

}
