<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Zend\Session\Validator\RemoteAddr;

/**
 * Description of ElasticRemoteAddr
 *
 * @author jasonpalmer
 */
class ElasticRemoteAddr extends RemoteAddr{
    
    public function isValid()
    {
        $this->log(__LINE__, "IPAddress: " . $this->getIpAddress() . " this->data: " . $this->getData());
        return ($this->getIpAddress() === $this->getData());
    }
    
    private function log($line, $msg){
        Logger::info("ElasticRemoteAddr", $line, $msg);
    }

    
}
