<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Application\Utility\Logger;
use Zend\Session\Validator\HttpUserAgent;

/**
 * Description of ElasticHttpUserAgent
 *
 * @author jasonpalmer
 */
class ElasticHttpUserAgent extends HttpUserAgent{
    
    public function isValid()
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT'])
                   ? $_SERVER['HTTP_USER_AGENT']
                   : null;
        
        $this->log(__LINE__, "UserAgent: " . $userAgent . " this->data: " . $this->getData());

        return ($userAgent === $this->getData());
    }
    
    private function log($line, $msg){
        Logger::info("ElasticHttpUserAgent", $line, $msg);
    }
    
}
