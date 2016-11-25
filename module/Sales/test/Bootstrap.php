<?php

namespace SalesTest;

use Zend\Loader\AutoloaderFactory;

chdir(dirname(__DIR__));

include __DIR__ . '/../../../vendor/autoload.php';

AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        ));


