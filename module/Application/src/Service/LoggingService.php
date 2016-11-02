<?php

namespace Application\Service;

use Application\Service\LoggingServiceInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Description of LoggingService
 *
 * @author jasonpalmer
 */
class LoggingService implements LoggingServiceInterface {
    
    protected $logger;
    
    public function __construct(){
        $writer = new Stream('php://stderr');
        $logger = new Logger();
        $logger->addWriter($writer);
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
