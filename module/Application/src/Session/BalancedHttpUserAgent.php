<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Session;

use Application\Utility\Logger;
use Zend\Session\Validator\HttpUserAgent;

/**
 * Description of BalancedHttpUserAgent
 *
 * @author jasonpalmer
 */
class BalancedHttpUserAgent extends HttpUserAgent{
    
    public function isValid()
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT'])
                   ? $_SERVER['HTTP_USER_AGENT']
                   : null;
        
        $same = strcmp($userAgent, $this->getData()) == 0;
        if(empty($same)){
            $this->log(__LINE__, "SessionValidation failed: " . $userAgent . " this->data: " . $this->getData());
        }
        return $same;
    }
    
    private function log($line, $msg){
        Logger::info("BalancedHttpUserAgent", $line, $msg);
    }
    
}
