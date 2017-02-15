<?php

namespace SalesSetupTest;

use Application\Utility\Logger;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Called first and erases the test.out log so we have one log for all suites.
 *
 * @author jasonpalmer
 */
class SetupTest extends AbstractHttpControllerTestCase{
    
    protected function setUp() {
        ob_start();
        ob_end_clean();
        $testlogfile = fopen(__DIR__ . "/../../../../data/logs/test.out", "w");
        fclose($testlogfile);
        date_default_timezone_set('UTC');
        Logger::info("CheckboxServiceTest", __LINE__, "Running TestSuite at " . date('l jS \of F Y h:i:s A'), TRUE);
    }
    
    public function testSetup(){
        $this->assertTrue(TRUE, "Test Suites log file could not be erased.");
    }
    
    public static function log($message){
        fwrite(STDERR, print_r($message, TRUE));
    }
    
}
