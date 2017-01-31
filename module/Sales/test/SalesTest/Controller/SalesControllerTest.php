<?php

namespace SalesTest\Controller;

use Application\Utility\Logger;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Description of SalesControllerTest
 *
 * @author jasonpalmer
 */
class SalesControllerTest extends AbstractControllerTestCase {
    
    protected $logger;

    public function setUp() {
        $this->setApplicationConfig(
                include __DIR__ . '/../../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testSalesCanBeAccessed() {
        Logger::info("SalesControllerTest", __LINE__, "Testing if sales can be accessed");
        $this->dispatch('/sales');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\SalesController');
        $this->assertControllerClass('SalesController');
        $this->assertMatchedRouteName('sales');
        //$this->
    }

}
