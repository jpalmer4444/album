<?php

namespace Application\Factory;

use Application\Service\TableService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of TableServiceFactory
 *
 * @author jasonpalmer
 */
class TableServiceFactory implements FactoryInterface{
    //put your code here
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        return new TableService($container);
    }

}
