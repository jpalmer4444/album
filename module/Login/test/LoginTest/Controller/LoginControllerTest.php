<?php

namespace LoginTest\Controller;

/**
 * Description of LoginControllerTest
 *
 * @author jasonpalmer
 */
class LoginControllerTest extends \Zend\Test\PHPUnit\Controller\AbstractControllerTestCase {

    public function setUp() {
        $this->setApplicationConfig(
                include __DIR__ . '/../../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testSalesCanBeAccessed() {
        $this->dispatch('/login');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Login');
        $this->assertControllerName('Login\Controller\LoginController');
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('login');
        //$this->
    }

}
