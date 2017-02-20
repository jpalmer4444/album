<?php

namespace Application\Factory;

use Application\Service\ReportService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LoginControllerFactory
 *
 * @author jasonpalmer
 */
class ReportServiceFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, mixed $options = null) {
        return new ReportService($container);
    }
    
}
