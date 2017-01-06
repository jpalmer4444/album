<?php

namespace SalesTest\Controller;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Description of ItemsControllerTest
 *
 * @author jasonpalmer
 */
class ItemsControllerTest extends AbstractControllerTestCase {
    
    protected $logger;

    public function setUp() {
        $this->setApplicationConfig(
                include __DIR__ . '/../../../../../config/application.config.php'
        );
        $writer = new Stream('php://stderr');
        $this->logger = new Logger();
        $this->logger->addWriter($writer);
        $this->logger->info("Creating ItemsControllerTest");
        parent::setUp();
    }

    public function testItemsCanBeAccessed() {
        $this->logger->info("Testing if items can be accessed");
        $this->dispatch('/items');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\ItemsController');
        $this->assertControllerClass('ItemsController');
        $this->assertMatchedRouteName('items');
        //$this->
    }

}
