<?php

namespace SalesTest\Service;

use Sales\Service\CheckboxService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Application;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of CheckboxServiceTest
 *
 * @author jasonpalmer
 */
class CheckboxServiceTest extends AbstractHttpControllerTestCase {

    protected $logger;
    protected $service;

    protected function setup() {

        $writer = new Stream(include __DIR__ . '/../../../../../data/logs/log.out');
        $logger = new Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $bootstrap = Application::init(include __DIR__ . '/../../../../../config/application.config.php');
        $this->logger->info("Testing");
        
        //mock LoggingService
        $logger_mock = $this->getMockBuilder('Application\Service\LoggingService')
                ->disableOriginalConstructor()
                ->getMock();

        $logger_mock->expects($this->once())
                ->method('info')
                ->will($this->returnSelf());
        
        $logger_mock->expects($this->once())
                ->method('debug')
                ->will($this->returnSelf());
        
        $logger_mock->expects($this->once())
                ->method('warn')
                ->will($this->returnSelf());
        
        //Mock FFMEntityManagerService
        $ffmentitymanagerservice_mock = $this->getMockBuilder('Application\Service\FFMEntityManagerService')
                ->disableOriginalConstructor()
                ->getMock();
        
        $ffmentitymanagerservice_mock->expects($this->once())
                ->method('getEntityManager()')
                ->will($this->returnSelf());
        
        $this->service = new CheckboxService($logger_mock, $ffmentitymanagerservice_mock);
        
    }
    
    public function testMyAction(){
        $this->service->findUser('jpalmer');
    }

}
