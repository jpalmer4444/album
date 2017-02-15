<?php

use Application\Cache\Predis;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return [
    'session_config' => [
        // Cookie expires in 24 hours
        'cookie_lifetime' => 36000,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class,
    ],
    'session' => [
        'config' => [
            'class' => SessionConfig::class,
            'options' => [
                'name' => 'pricing_app_v1',
            ],
        ],
        'storage' => SessionArrayStorage::class,
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            // Configures the default SessionManager instance
        'Zend\Session\ManagerInterface' => 'Zend\Session\Service\SessionManagerFactory',
        // Provides session configuration to SessionManagerFactory
        'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
        ],
    ],
];
