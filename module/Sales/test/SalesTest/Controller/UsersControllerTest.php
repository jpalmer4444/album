<?php

namespace SalesTest\Controller;

use Application\Utility\Logger;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Description of UsersControllerTest
 *
 * @author jasonpalmer
 */
class UsersControllerTest extends AbstractControllerTestCase {
    
    protected $logger;

    public function setUp() {
        $this->setApplicationConfig(
                include __DIR__ . '/../../../../../config/application.config.php'
        );
        Logger::info("UsersControllerTest", __LINE__, "Creating UsersControllerTest");
        parent::setUp();
    }

    public function testUsersCanBeAccessed() {
        Logger::info("UsersControllerTest", __LINE__, "Testing if users can be accessed");
        $this->dispatch('/users');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\UsersController');
        $this->assertControllerClass('UsersController');
        $this->assertMatchedRouteName('users');
        //$this->
    }

}
