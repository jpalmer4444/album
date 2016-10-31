<?php

use Ajax\Controller\Sales\ItemsController;

return array(
    'router' => array(
        'routes' => array(
            'ajax' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/ajax/items',
                    'defaults' => array(
                        'controller' => ItemsController::class,
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
