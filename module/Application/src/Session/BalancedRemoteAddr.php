<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Session;

use Application\Utility\Logger;
use Zend\Session\Validator\RemoteAddr;

/**
 * Description of BalancedRemoteAddr
 *
 * @author jasonpalmer
 */
class BalancedRemoteAddr extends RemoteAddr{
    
    public static function getUseProxy() {
        return true;
    }

    //UserAgent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36
    
    public function isValid()
    {
        $this->log(__LINE__, "IPAddress: " . $this->getIpAddress() . " this->data: " . $this->getData());
        return ($this->getIpAddress() === $this->getData());
    }
    
    private function log($line, $msg){
        Logger::info("BalancedRemoteAddr", $line, $msg);
    }

    
}
