<?php

namespace AjaxTest\Service\Sales;

use Ajax\Service\Sales\ItemsFilterTableArrayService;
use Application\Utility\Logger;
use Application\Utility\Strings;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of CheckboxServiceTest
 *
 * @author jasonpalmer
 */
class ItemsFilterTableArrayServiceTest extends AbstractHttpControllerTestCase {

    protected $logger;
    protected $interface;

    protected function setup() {
        Logger::info("ItemsFilterTableArrayServiceTest", __LINE__, "Setting up ItemsFilterTableArrayServiceTest", TRUE);  
    }
    
    public function testQueryPages() {
        Logger::stderr(ItemsFilterTableArrayServiceTest::class, __LINE__, "Testing ItemsFilterTableArrayServiceTest::QUERY_PAGES.");
        $query = ItemsFilterTableArrayService::QUERY_PAGES;
        $eol = Strings::detectEol($query, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string ItemsFilterTableArrayServiceTest::QUERY_PAGES");
        $this->assertNotNull($query);
    }
    
    public function testQueryOverrides() {
        Logger::stderr(ItemsFilterTableArrayServiceTest::class, __LINE__, "Testing ItemsFilterTableArrayServiceTest::QUERY_OVERRIDES.");
        $query = ItemsFilterTableArrayService::QUERY_OVERRIDES;
        $eol = Strings::detectEol($query, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string ItemsFilterTableArrayServiceTest::QUERY_OVERRIDES");
        $this->assertNotNull($query);
    }

}
