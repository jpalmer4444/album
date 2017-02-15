<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Cache;

use Zend\Cache\Storage\Adapter\AdapterOptions;

class PredisOptions extends AdapterOptions {

    protected $host;
    
    protected $port;
    
    protected $timeout;
    
    protected $database;
    
    public function __construct($options = array()) {
        
        parent::__construct($options);
        
        $defaultOptions = array(
            'host' => 'localhost',
            'port' => 6379,
            'ttl' => 86400,
            'database' => 0
        );
        
        $mergedOptions = array_merge(array_key_exists('redis_config', $options) ? $options['redis_config'] : $options, $defaultOptions);
        
        $this->host = $mergedOptions['host'];
        $this->port = $mergedOptions['port'];
        $this->timeout = $mergedOptions['ttl'];
        $this->database = $mergedOptions['database'];
    }

    public function getHost() {
        return $this->host;
    }

    public function getPort() {
        return $this->port;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
        return $this;
    }

    public function setDatabase($database) {
        $this->database = $database;
        return $this;
    }
}
