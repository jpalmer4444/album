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
        'cookie_secure' => true
    ],
    'session_manager' => [
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
        ],
    ],
    'service_manager' => [
        'factories' => [
        ],
    ],
];
