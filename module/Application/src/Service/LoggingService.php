<?php

namespace Application\Service;

use Application\Service\LoggingServiceInterface;
use Zend\Log\Logger;

/**
 * Description of LoggingService
 *
 * @author jasonpalmer
 */
class LoggingService implements LoggingServiceInterface {
    
    protected $logger;
    
    public function __construct(Logger $logger){
        $this->logger = $logger;
        
    }
    
    public function debug($message) {
        $this->logger->debug($message);
    }

    public function error($message) {
        $this->logger->error($message);
    }

    public function info($message) {
        $this->logger->info($message);
    }

    public function warn($message) {
        $this->logger->warn($message);
    }

}
