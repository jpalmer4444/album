<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'price-override-report' => array(
                    'options' => array(
                        'route'    => 'price override [--verbose|-v]',
                        'defaults' => array(
                            'controller' => 'Command\Controller\Reporting\PriceOverrideController',
                            'action'     => 'priceoverridereport'
                        )
                    )
                )
            )
        )
    )
);