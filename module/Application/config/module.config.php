<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => "Login\Controller\LoginController", //<--Changes Home Page to 
                        'action' => 'login',
                    ],
                ],
            ],
            'application' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => "Application\Controller\IndexController",
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'user-reset-password' => array(
                    'options' => array(
                        'route' => 'user resetpassword [--verbose|-v] <userEmail>',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'resetpassword'
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'log' => array(
    'Log\App' => array(
        'writers' => array(
            array(
                'name' => 'stream',
                'priority' => 1000,
                'options' => array(
                    'stream' => __DIR__ . '/../../../data/logs/log.out'
                )
            )
        )
    )
)
];
