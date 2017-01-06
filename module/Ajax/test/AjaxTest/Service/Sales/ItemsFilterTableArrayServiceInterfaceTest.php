<?php

namespace AjaxTest\Service\Sales;

use Ajax\Service\Sales\ItemsFilterTableArrayServiceInterface;
use Application\Utility\Logger;
use Application\Utility\Strings;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of CheckboxServiceTest
 *
 * @author jasonpalmer
 */
class ItemsFilterTableArrayServiceInterfaceTest extends AbstractHttpControllerTestCase {

    protected $logger;
    protected $interface;

    protected function setup() {
        Logger::info("ItemsFilterTableArrayServiceInterfaceTest", __LINE__, "Setting up ItemsFilterTableArrayServiceInterfaceTest", TRUE);  
    }
    
    public function testQueryPages() {
        Logger::stderr(ItemsFilterTableArrayServiceInterfaceTest::class, __LINE__, "Testing ItemsFilterTableArrayServiceInterfaceTest::QUERY_PAGES.");
        $query = ItemsFilterTableArrayServiceInterface::QUERY_PAGES;
        $eol = Strings::detectEol($query, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string ItemsFilterTableArrayServiceInterfaceTest::QUERY_PAGES");
        $this->assertNotNull($query);
    }
    
    public function testQueryOverrides() {
        Logger::stderr(ItemsFilterTableArrayServiceInterfaceTest::class, __LINE__, "Testing ItemsFilterTableArrayServiceInterfaceTest::QUERY_OVERRIDES.");
        $query = ItemsFilterTableArrayServiceInterface::QUERY_OVERRIDES;
        $eol = Strings::detectEol($query, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string ItemsFilterTableArrayServiceInterfaceTest::QUERY_OVERRIDES");
        $this->assertNotNull($query);
    }

}
