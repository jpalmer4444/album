<?php

namespace Sales\Factory;

use Sales\Service\SalesFormService;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of SalesFormServiceFactory
 *
 * @author jasonpalmer
 */
class SalesFormServiceFactory {

    public function __invoke(ServiceManager $sm) {
        return new SalesFormService();
    }

}
