<?php

namespace SalesTest\Controller;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
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
        $writer = new Stream('php://stderr');
        $this->logger = new Logger();
        $this->logger->addWriter($writer);
        $this->logger->info("Creating UsersControllerTest");
        parent::setUp();
    }

    public function testUsersCanBeAccessed() {
        $this->logger->info("Testing if users can be accessed");
        $this->dispatch('/users');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Sales');
        $this->assertControllerName('Sales\Controller\UsersController');
        $this->assertControllerClass('UsersController');
        $this->assertMatchedRouteName('users');
        //$this->
    }

}
