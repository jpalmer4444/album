<?php

namespace SalesTest\Service;

use DataAccess\FFM\Entity\ItemTableCheckbox;
use DateTime;
use Sales\Service\CheckboxService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
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
        
        $writer = new Stream(__DIR__ . '/../../../../../data/logs/test.out');
        $logger = new Logger();
        $logger->addWriter($writer);
        
        $this->logger = $logger;
        $this->logger->info("Testing CheckboxServiceTest");
        
        //mock LoggingService
        $logger_mock = $this->getMockBuilder('Application\Service\LoggingService')
                ->disableOriginalConstructor()
                ->getMock();

        $logger_mock->expects($this->any())
                ->method('info')
                ->will($this->returnSelf());
        
        $logger_mock->expects($this->any())
                ->method('debug')
                ->will($this->returnSelf());
        
        $logger_mock->expects($this->any())
                ->method('warn')
                ->will($this->returnSelf());
        
        //Mock CheckboxRepositoryImpl
        $checkboxrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\ItemTableCheckboxRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $checkboxrepository_mock->expects($this->any())
                ->method('persist')
                ->will($this->returnSelf());
        
        $checkboxrepository_mock->expects($this->any())
                ->method('flush')
                ->will($this->returnSelf());
        
        //IDS mock
        $now = new DateTime();
        $salesperson1 = ModelMocks::getMockUser("1", $now, 1);
        $customer1 = ModelMocks::getMockCustomer("1", $now, 1);
        $checkbox1 = new ItemTableCheckbox();
        $checkbox1->setChecked(true);
        $checkbox1->setCreated($now);
        $checkbox1->setCustomer($customer1);
        $checkbox1->setId(1);
        $checkbox1->setLogger($this->logger);
        $sku = 123;
        $product1 = ModelMocks::getMockProduct("1", $now, 1, $sku, TRUE, $customer1);
        $checkbox1->setProduct($product1);
        $rowplusitemspage = ModelMocks::getRowPlusItemsPage($customer1, $salesperson1, 1, $sku, $now, $this->logger);
        $checkbox1->setRowPlusItemsPage($rowplusitemspage);
        $checkbox1->setSalesperson($salesperson1);
        $allIDsByCustomerIdAndSalesperson_mock = array($checkbox1);
        
        $checkboxrepository_mock->expects($this->atLeast(0))
                ->method('getAllIDsByCustomerIdAndSalesperson')
                ->will($this->returnValue($allIDsByCustomerIdAndSalesperson_mock));
        
        $checkboxrepository_mock->expects($this->atLeast(0))
                ->method('removeCheckbox')
                ->will($this->returnSelf());
        
        $checkboxrepository_mock->expects($this->atLeast(0))
                ->method('findCheckbox')
                ->will($this->returnValue($checkbox1));
        
        //Mock UserRepositoryImpl
        $userrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $userrepository_mock->expects($this->atLeast(0))
                ->method('findUser')
                ->will($this->returnValue($salesperson1));
        
        //Mock CustomerRepositoryImpl
        $customerrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $customerrepository_mock->expects($this->atLeast(0))
                ->method('findCustomer')
                ->will($this->returnValue($customer1));
        
        //Mock ProductRepositoryImpl
        $productrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $productrepository_mock->expects($this->any())
                ->method('findProduct')
                ->will($this->returnValue($product1));
        
        //Mock RowPlusItemsPageRepositoryImpl
        $rowplusitemspagerepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $rowplusitemspagerepository_mock->expects($this->any())
                ->method('findRowPlusItemsPage')
                ->will($this->returnValue($rowplusitemspage));
        
        $this->service = new CheckboxService($logger_mock, $checkboxrepository_mock, $userrepository_mock, $customerrepository_mock, $productrepository_mock, $rowplusitemspagerepository_mock);
        
    }
    
    public function testAddRemovedID(){
        $this->assertNull($this->service->addRemovedID("P1", 1, 'Mock salesperson 1'), "CheckboxService->addRemovedID(\$id, \$customerid, \$username) returns null");
    }
    
    public function testRemoveRemovedID(){
        $this->assertNull($this->service->removeRemovedID("A1", 1, 'Mock salesperson 1'), "CheckboxService->removeRemovedID(\$id, \$customerid, \$username) returns null");
    }
    
    public function testGetRemovedIDS(){
        $this->assertNotNull($this->service->getRemovedIDS(1, "Mock Salesperson 1"), "CheckboxService->getRemovedIDS(\$id, \$username) returns a User");
    }

}
