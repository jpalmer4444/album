<?php

namespace SalesTest\Controller;

use Application\Utility\Logger;
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
        parent::setUp();
    }

    public function testItemsCanBeAccessed() {
        Logger::info("ItemsControllerTest", __LINE__, "Testing if items can be accessed");
        $this->dispatch('/items');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\ItemsController');
        $this->assertControllerClass('ItemsController');
        $this->assertMatchedRouteName('items');
        //$this->
    }

}
