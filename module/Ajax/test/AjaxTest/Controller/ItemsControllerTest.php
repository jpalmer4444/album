<?php

namespace AjaxTest\Controller;

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
        $this->logger->info("Creating (Ajax) ItemsControllerTest");
        parent::setUp();
    }

    public function testAjaxItemsCanBeAccessed() {
        $this->logger->info("Testing if ajax/items can be accessed");
        $this->dispatch('/ajax/items');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Ajax');
        $this->assertControllerName('Ajax\Controller\Sales\ItemsController');
        $this->assertControllerClass('ItemsController');
        $this->assertMatchedRouteName('ajax');
        //$this->
    }

}
