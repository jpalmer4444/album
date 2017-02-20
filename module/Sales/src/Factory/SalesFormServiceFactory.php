<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\Service\SalesFormService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of SalesFormServiceFactory
 *
 * @author jasonpalmer
 */
class SalesFormServiceFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new SalesFormService();
    }

}
