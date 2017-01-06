<?php

namespace SalesTest\Controller;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
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
        $writer = new Stream('php://stderr');
        $this->logger = new Logger();
        $this->logger->addWriter($writer);
        $this->logger->info("Creating SalesControllerTest");
        parent::setUp();
    }

    public function testSalesCanBeAccessed() {
        $this->logger->info("Testing if sales can be accessed");
        $this->dispatch('/sales');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\SalesController');
        $this->assertControllerClass('SalesController');
        $this->assertMatchedRouteName('sales');
        //$this->
    }

}
