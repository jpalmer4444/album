<?php

/*
 * Logger for webapp.
 */

namespace Application\Service;

/**
 *
 * @author jasonpalmer
 */
interface LoggingServiceInterface {
    
    public function info($message);
    public function debug($message);
    public function warn($message);
    public function error($message);
    
}
