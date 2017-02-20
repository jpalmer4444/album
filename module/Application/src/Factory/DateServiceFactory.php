<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory;

use Application\Service\DateService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of DateServiceFactory
 *
 * @author jasonpalmer
 */
class DateServiceFactory implements FactoryInterface{
    //put your code here
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $config = $container->get('Config');
        return new DateService($config);
    }

}
