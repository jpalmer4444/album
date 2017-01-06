<?php

namespace Application\Utility;

use Zend\Log\Writer\Stream;

/**
 * Description of Logger
 *
 * @author jasonpalmer
 */
class Logger {
    
    private static function log($level, $classname, $linenumber, $message, $test = FALSE){
        $now = date("D n/j/o h:i:sA");
        fwrite(fopen(__DIR__ . "/../../../../data/logs/" . (empty($test) ? "log.out" : "test.out"), "a"), "[" . strtoupper($level) . ":" . $now . ":" . $classname . ":" . $linenumber .  "] " . $message . "\n");
    }
    
    public static function info($classname, $linenumber, $message, $test = FALSE){
        Logger::log("info", $classname, $linenumber, $message, $test);
    }
    
    public static function error($classname, $linenumber, $message, $test = FALSE){
        Logger::log("error", $classname, $linenumber, $message, $test);
    }
    
    public static function warn($classname, $linenumber, $message, $test = FALSE){
        Logger::log("warn", $classname, $linenumber, $message, $test);
    }
    
    public static function debug($classname, $linenumber, $message, $test = FALSE){
        Logger::log("debug", $classname, $linenumber, $message, $test);
    }
    
    public static function stderr($classname, $linenumber, $message){
        $writer = new Stream('php://stdout');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $now = date("D n/j/o h:i:sA");
        $idx = strrpos($classname, "\\");
        $clazz = substr($classname, ++$idx);
        $logger->info("[" . $now . ":" . $clazz . ":" . $linenumber .  "] " . $message);
    }
    
}
