<?php

namespace SalesTest\Service;

use Application\Utility\Logger;
use DateTime;
use Sales\Service\SalesFormService;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

/**
 * Description of SalesFormServiceTest
 *
 * @author jasonpalmer
 */
class SalesFormServiceTest extends AbstractHttpControllerTestCase {

    protected $service;
    
    protected $userrepository_mock;
    protected $customerrepository_mock;
    protected $myauthstorage_mock;
    protected $rowplusitemspagerepository_mock;
    protected $form_mock;

    public function setup()
    {
        
        Logger::info("SalesFormServiceTest", __LINE__, "Testing SalesFormServiceTest", TRUE);
        
        $this->myauthstorage_mock = $this->getMockBuilder('Login\Model\MyAuthStorage')
                ->disableOriginalConstructor()
                ->getMock();
        
        //mock getUserOrSalespersonInPlay
        $this->myauthstorage_mock->expects($this->any())
                ->method('getUserOrSalespersonInPlay')
                ->will($this->returnValue(ModelMocks::getMockUser("1", new DateTime(), 1)));
        
        //now mock out CustomerRepositoryImpl findCustomer to always return a Customer IF an id is passed.
        $this->customerrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->customerrepository_mock->expects($this->atLeast(0))
                ->method('findCustomer')->with($this->equalTo(1))
                ->will($this->returnValue(ModelMocks::getMockCustomer("1", new DateTime(), 1)));
        
        //mock user has username "Mock username $append"
        //Mock UserRepositoryImpl
        $this->userrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->userrepository_mock->expects($this->atLeast(0))
                ->method('findUser')->with($this->equalTo("Mock username 1"))
                ->will($this->returnValue(ModelMocks::getMockUser("1", new DateTime(), 1)));
        
        //Mock RowPlusItemsPageRepositoryImpl
        $this->rowplusitemspagerepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->rowplusitemspagerepository_mock->expects($this->any())
                ->method('persistAndFlush')
                ->will($this->returnSelf());
        
        //Mock Form
        $this->form_mock = $this->getMockBuilder('Zend\Form\Form')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->form_mock->expects($this->atLeast(0))
                ->method('isValid')
                ->will($this->returnValue(TRUE));
        
        $data = [
            "product"=>"productname",
            "description"=>"shortescription",
            "overrideprice"=>6.98,
            "sku"=>"0000",
            "uom"=>"uom",
            "comment"=>"comment"
            ];
        
        $this->form_mock->expects($this->atLeast(0))
                ->method('getData')
                ->will($this->returnValue($data));
        
        $this->service = new SalesFormService();
    }

    
    
    public function testAssembleRowPlusItemsPageAndArray(){
        $jsonModelArray = array();
        $jsonViewModel = $this->service->assembleRowPlusItemsPageAndArray($this->myauthstorage_mock, $this->customerrepository_mock, $this->userrepository_mock, $this->rowplusitemspagerepository_mock, $this->form_mock, $jsonModelArray, 1);
        $this->assertNotNull($jsonViewModel, "failed to return not null");
        $this->assertInstanceOf(JsonModel::class, $jsonViewModel, "failed to instantiate a JSONModel");
        $this->assertArrayHasKey("id", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key id");
        $this->assertArrayHasKey("productname", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key productname");
        $this->assertArrayHasKey("shortescription", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key shortescription");
        $this->assertArrayHasKey("comment", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key comment");
        $this->assertArrayHasKey("overrideprice", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key overrideprice");
        $this->assertArrayHasKey("uom", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key uom");
        $this->assertArrayHasKey("qty", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key qty");
        $this->assertArrayHasKey("option", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key option");
        $this->assertArrayHasKey("wholesale", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key wholesale");
        $this->assertArrayHasKey("retail", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key retail");
        $this->assertArrayHasKey("status", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key status");
        $this->assertArrayHasKey("saturdayenabled", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key saturdayenabled");
        $this->assertArrayHasKey("success", $jsonViewModel->getVariables(), "failed to instantiate a JSONModel with key success");
        
    }

}
