<?php

namespace Application\Factory;

use Application\Service\CookieService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of CookieServiceFactory
 *
 * @author jasonpalmer
 */
class CookieServiceFactory implements FactoryInterface{
    //put your code here
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $config = $container->get('Config');
        return new CookieService($config);
    }

}
