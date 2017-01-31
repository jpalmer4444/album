<?php

namespace AjaxTest\Controller;

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
        Logger::info("ItemsControllerTest", __LINE__, 'Creating (Ajax) ItemsControllerTest');
        parent::setUp();
    }

    public function testAjaxItemsCanBeAccessed() {
        Logger::info("ItemsControllerTest", __LINE__, "Testing if ajax/items can be accessed");
        $this->dispatch('/ajax/items');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Ajax');
        $this->assertControllerName('Ajax\Controller\Sales\ItemsController');
        $this->assertControllerClass('ItemsController');
        $this->assertMatchedRouteName('ajax');
        //$this->
    }

}
