<?php

namespace SalesTest\Service;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of SalesFormServiceTest
 * 
 * SalesFormService is a service that deals with forms and form related operations specific to the Sales module.
 * Forms include:
 * 1.
 *
 * @author jasonpalmer
 */
class SalesFormServiceTest extends AbstractHttpControllerTestCase {

    protected $logger;
    protected $service;

    protected function setup() {
        
        $writer = new Stream(__DIR__ . '/../../../../../data/logs/test.out');
        $logger = new Logger();
        $logger->addWriter($writer);
        
        $this->logger = $logger;
        $this->logger->info("Testing SalesFormServiceTest");
        
        
        
        $this->service = NULL;
        
    }
    
    public function testPostRowPlusItemsPage(){
        
    }

}
