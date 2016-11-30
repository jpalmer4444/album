<?php

namespace SalesTest\Controller;

use DataAccess\FFM\Entity\Customer;
use DataAccess\FFM\Entity\Product;
use DataAccess\FFM\Entity\User;
use DataAccess\FFM\Entity\UserProduct;
use DateTime;
use Exception;
use Sales\Controller\ItemsController;
use Sales\DTO\RowPlusItemsPageForm;
use Zend\Http\Request;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Application;
use Zend\Mvc\Console\Router\RouteMatch;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ItemsControllerTest extends AbstractHttpControllerTestCase {

    protected $traceError = true;
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $logger;

    protected function setUp() {
        try {
            $writer = new Stream(include __DIR__ . '/../../../../../data/logs/log.out');
            $logger = new Logger();
            $logger->addWriter($writer);
            $this->logger = $logger;
            $bootstrap = Application::init(include __DIR__ . '/../../../../../config/application.config.php');
            $this->logger->info("Testing");
            //mock loggingService
            $logger_mock = $this->getMockBuilder('Application\Service\LoggingService')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$logger_mock instantiated.");

            $logger_mock->expects($this->once())
                    ->method('info')
                    ->will($this->returnSelf());

            $this->logger->info("\$logger_mock mocking.");

            //mock myauthstorage
            $myauthstorage_mock = $this->getMockBuilder('Login\Model\MyAuthStorage')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$myauthstorage_mock instantiated.");

            $user_mock = new User();
            $user_mock->setUsername("salesperson_mock");
            $user_mock->setSalespersonname("salesperson_mock");
            $user_mock->setPhone1("999-999-9999");
            $user_mock->setCreated(strtotime("now"));
            $user_mock->setUpdated(strtotime("now"));
            $user_mock->setSales_attr_id(0);
            $user_mock->setLastlogin(strtotime("now"));
            $user_mock->setEmail("salesperson_mock@fultonfishmarket.com");
            $user_mock->setPassword('$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO');

            $this->logger->info("\$user_mock instantiated.");

            $admin_mock = new User();
            $admin_mock->setUsername("admin_mock");
            $admin_mock->setSalespersonname("admin_mock");
            $admin_mock->setPhone1("999-999-9999");
            $admin_mock->setCreated(strtotime("now"));
            $admin_mock->setUpdated(strtotime("now"));
            $admin_mock->setSales_attr_id(0);
            $admin_mock->setLastlogin(strtotime("now"));
            $admin_mock->setEmail("admin_mock@fultonfishmarket.com");
            $admin_mock->setPassword('$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO');

            $this->logger->info("\$admin_mock instantiated.");

            $myauthstorage_mock->expects($this->once())
                    ->method('getSalespersonInPlay')
                    ->will($this->returnValue($user_mock));

            $this->logger->info("\$myauthstorage_mock getSalespersonInPlay() mocked.");

            $myauthstorage_mock->expects($this->once())
                    ->method('getUser')
                    ->will($this->returnValue($admin_mock));

            $this->logger->info("\$myauthstorage_mock getUser() mocked.");

            //mock AbstractPluginManager
            $formManager_mock = $this->getMockBuilder('Zend\ServiceManager\AbstractPluginManager')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$formManager_mock instantiated.");

            $rowPlusItemsPageForm_mock = new RowPlusItemsPageForm();

            $this->logger->info("\$rowPlusItemsPageForm_mock instantiated.");

            $formManager_mock->expects($this->once())
                    ->method('get')
                    ->will($this->returnValue($rowPlusItemsPageForm_mock));

            $this->logger->info("\$formManager_mock get() mocked.");

            //mock UserRepositoryImpl
            $userrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$userrepository_mock instantiated.");

            $userrepository_mock->expects($this->once())
                    ->method('findUser')
                    ->will($this->returnValue($user_mock));

            $this->logger->info("\$userrepository_mock findUser() mocked.");

            //mock RowPlusItemsPageRepositoryImpl
            $rowplusitemspagerepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$rowplusitemspagerepository_mock instantiated.");

            $rowplusitemspagerepository_mock->expects($this->once())
                    ->method('persistAndFlush')
                    ->will($this->returnSelf());

            $this->logger->info("\$rowplusitemspagerepository_mock persistAndFlush mocked.");

            //mock CustomerRepositoryImpl
            $customerrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$customerrepository_mock instantiated.");

            $customer_mock = new Customer();
            $customer_mock->setCompany("Mock Company");
            $customer_mock->setCreated(strtotime("now"));
            $customer_mock->setEmail("customer_mock@fultonfishmarket.com");
            $customer_mock->setId(0);
            $customer_mock->setName("Mock Customer Name");
            $customer_mock->setUpdated(strtotime("now"));
            $customer_mock->setVersion(0);

            $this->logger->info("\$customer_mock instantiated.");

            $customerrepository_mock->expects($this->once())
                    ->method('findCustomer')
                    ->will($this->returnValue($customer_mock));

            $this->logger->info("\$customerrepository_mock findCustomer mocked.");

            //mock ProductRepositoryImpl
            $productrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl')
                    ->disableOriginalConstructor()
                    ->getMock();

            $this->logger->info("\$productrepository_mock instantiated.");

            $product_mock = new Product();
            //$product_mock->setComment("Mock Comment");
            $product_mock->setDescription("Mock Description");
            $product_mock->setId(0);
            //$product_mock->setOption("Mock Option");
            $product_mock->setProductname("Mock Product Name");
            $product_mock->setQty(0);
            $product_mock->setRetail(999);
            $product_mock->setSaturdayenabled(true);
            $product_mock->setSku("00000");
            $product_mock->setStatus(true);
            $product_mock->setUom("lb");
            $product_mock->setVersion(0);
            $product_mock->setWholesale(777);
            $now = new DateTime();
            $product_mock->set_created($now);
            $product_mock->set_updated($now);
            
            $productrepository_mock->expects($this->once())
                    ->method('addedProduct')
                    ->will($this->returnValue($product_mock));

            $this->logger->info("\$product_mock instantiated.");

            $userproductrepository_mock = $this->getMockBuilder('DataAccess\FFM\Entity\Repository\Impl\UserProductRepositoryImpl')
                    ->disableOriginalConstructor()
                    ->getMock();
            
            $userproductrepository_mock->expects($this->once())
                    ->method('findUserProduct')
                    ->will($this->returnValue(new UserProduct()));

            $this->controller = new ItemsController(
                    $logger_mock, $myauthstorage_mock, $formManager_mock, $userrepository_mock, $rowplusitemspagerepository_mock, $customerrepository_mock, $productrepository_mock, $userproductrepository_mock
            );
        } catch (Exception $exc) {
            $this->logger->info($exc->getTraceAsString());
            throw $exc;
        }

        $this->logger->info("Mocks");
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'items'));
        $this->event = $bootstrap->getMvcEvent();
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setEventManager($bootstrap->getEventManager());
        $this->controller->setServiceLocator($bootstrap->getServiceManager());
    }

    public function testIndexActionCanBeAccessed() {

        try {
            $this->dispatch('/items');
            $this->assertResponseStatusCode(200);

            $this->assertModuleName('Sales');
            $this->assertControllerName('Sales\Controller\Items');
            $this->assertControllerClass('ItemsController');
            $this->assertMatchedRouteName('items');
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        }

}
