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
                ),
                'redis-commands-report' => array(
                    'options' => array(
                        'route'    => 'redis command <cmd> [--verbose|-v] [--host=|-h=] [--port=|-p=] [--database=|-d=]',
                        'defaults' => array(
                            'controller' => 'Command\Controller\Reporting\RedisCommandController',
                            'action'     => 'rediscommandAction'
                        )
                    )
                )
            )
        )
    )
);