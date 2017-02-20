<?php

namespace Application\Service;

use Application\Utility\Logger;

/**
 * Description of BaseService
 *
 * @author jasonpalmer
 */
class BaseService {
    
    protected function log($name, $line, $msg){
        Logger::info($name, $line, $msg);
    }
    
}
