<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Cache;

use Application\Utility\Logger;
use Exception;
use Predis\Client;
use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Cache\Storage\Adapter\AdapterOptions;
use Zend\Cache\Storage\ClearByNamespaceInterface;
use Zend\Cache\Storage\ClearByPrefixInterface;
use Zend\Cache\Storage\FlushableInterface;
use Zend\Cache\Storage\TotalSpaceCapableInterface;
use Zend\Session\Exception\RuntimeException;
use Zend\Stdlib\Exception\InvalidArgumentException;

class Predis extends AbstractAdapter implements ClearByNamespaceInterface, ClearByPrefixInterface, FlushableInterface, TotalSpaceCapableInterface {

    protected $predisClient;
    protected $predisOptions;

    /**
     * The namespace separator
     * @var string
     */
    protected $namespaceSeparator = ':';

    public function __construct(AdapterOptions $options) {
        parent::__construct($options);
        $parameters = [
            "host" => $options['host'],
            "port" => $options['port'],
            "ttl" => $options['ttl']
        ];
        $options = [
            'parameters' => [
                'database' => $options['database'],
            ],
        ];
        
        $client = new Client($parameters, $options);
        $this->predisClient = $client;
        $this->predisOptions = $options;
    }

    /**
     * Set namespace.
     *
     * The option Redis::OPT_PREFIX will be used as the namespace.
     * It can't be longer than 128 characters.
     *
     * @param string $namespace Prefix for each key stored in redis
     * @return \Zend\Cache\Storage\Adapter\PredisOptions
     *
     * @see AdapterOptions::setNamespace()
     * @see PredisOptions::setPrefixKey()
     */
    public function setNamespace($namespace) {
        $namespace = (string) $namespace;

        if (128 < strlen($namespace)) {
            throw new InvalidArgumentException(sprintf(
                    '%s expects a prefix key of no longer than 128 characters', __METHOD__
            ));
        }

        return parent::setNamespace($namespace);
    }

    /**
     * Set namespace separator
     *
     * @param  string $namespaceSeparator
     * @return PredisOptions
     */
    public function setNamespaceSeparator($namespaceSeparator) {
        $namespaceSeparator = (string) $namespaceSeparator;
        if ($this->namespaceSeparator !== $namespaceSeparator) {
            $this->triggerOptionEvent('namespace_separator', $namespaceSeparator);
            $this->namespaceSeparator = $namespaceSeparator;
        }
        return $this;
    }

    /**
     * Get namespace separator
     *
     * @return string
     */
    public function getNamespaceSeparator() {
        return $this->namespaceSeparator;
    }

    protected function internalGetItem(&$normalizedKey, &$success = null, &$casToken = null) {
        $val = $this->predisClient->get($this->namespacePrefix . $normalizedKey);
        if ($val == null) {
            $success = false;
        } else {
            $success = true;
        }
        return unserialize($val);
    }

    protected function internalRemoveItem(&$normalizedKey) {
        try {
            $this->predisClient->del($this->namespacePrefix . $normalizedKey);
        } catch (Exception $e) {
            throw new RuntimeException("PredisClient Error!", $e->getCode(), $e);
        }
    }

    protected function internalSetItem(&$normalizedKey, &$value) {
        Logger::info("Predis", __LINE__, 'InternalSetItem called.'); 
        $this->predisClient->set($this->namespacePrefix . $normalizedKey, serialize($value));
        if ($this->getOptions()->getTimeout()) {
            $this->predisClient->expire($this->namespacePrefix . $normalizedKey, $this->getOptions()->getTtl());
        }
        return false;
    }

    public function clearByNamespace($namespace) {
        try {
            $this->predisClient->del($this->namespacePrefix);
        } catch (Exception $e) {
            throw new RuntimeException("PredisClient Error!", $e->getCode(), $e);
        }
    }

    public function clearByPrefix($prefix) {
        try {
            $this->predisClient->del($this->namespacePrefix . $prefix);
        } catch (Exception $e) {
            throw new RuntimeException("PredisClient Error!", $e->getCode(), $e);
        }
    }

    public function flush() {
        return $this->predisClient->flushdb();
    }

    public function getTotalSpace() {
        return 104857600;
    }

}
