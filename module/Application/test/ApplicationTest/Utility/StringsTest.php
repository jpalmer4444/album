<?php

namespace ApplicationTest\Utility;

use Application\Utility\Logger;
use Application\Utility\Strings;
use PHPUnit\Framework\TestCase;

/**
 * Description of StringsTest
 *
 * @author jasonpalmer
 */
class StringsTest extends TestCase{
    
    public function testConstFFMEntityManager() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::FFM_ENTITY_MANAGER.");
        $entityManager = Strings::FFM_ENTITY_MANAGER;
        $eol = Strings::detectEol($entityManager, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::FFM_ENTITY_MANAGER");
        $this->assertEquals($entityManager, Strings::FFM_ENTITY_MANAGER, "InCorrect value for Strings::FFM_ENTITY_MANAGER");
        $this->assertNotNull($entityManager);
    }
    
    public function testConstSessionManager() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SESSION_MANAGER.");
        $sessionManager = Strings::SESSION_MANAGER;
        $eol = Strings::detectEol($sessionManager, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SESSION_MANAGER");
        $this->assertEquals($sessionManager, Strings::SESSION_MANAGER, "InCorrect value for Strings::SESSION_MANAGER");
        $this->assertNotNull($sessionManager);
    }
    
    public function testConstReportService() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::REPORT_SERVICE.");
        $reportService = Strings::REPORT_SERVICE;
        $eol = Strings::detectEol($reportService, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::REPORT_SERVICE");
        $this->assertEquals($reportService, Strings::REPORT_SERVICE, "InCorrect value for Strings::REPORT_SERVICE");
        $this->assertNotNull($reportService);
    }
    
    public function testConstRestService() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::REST_SERVICE.");
        $restService = Strings::REST_SERVICE;
        $eol = Strings::detectEol($restService, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::REST_SERVICE");
        $this->assertEquals($restService, Strings::REST_SERVICE, "InCorrect value for Strings::REST_SERVICE");
        $this->assertNotNull($restService);
    }
    
    public function testConstConfig() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::CONFIG.");
        $config = Strings::CONFIG;
        $eol = Strings::detectEol($config, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::CONFIG");
        $this->assertEquals($config, Strings::CONFIG, "InCorrect value for Strings::CONFIG");
        $this->assertNotNull($config);
    }
    
    public function testConstPricingConfig() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::PRICING_CONFIG.");
        $pricingConfig = Strings::PRICING_CONFIG;
        $eol = Strings::detectEol($pricingConfig, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::PRICING_CONFIG");
        $this->assertEquals($pricingConfig, Strings::PRICING_CONFIG, "InCorrect value for Strings::PRICING_CONFIG");
        $this->assertNotNull($pricingConfig);
    }
    
    public function testConstControllers() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::CONTROLLERS.");
        $controllers = Strings::CONTROLLERS;
        $eol = Strings::detectEol($controllers, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::CONTROLLERS");
        $this->assertEquals($controllers, Strings::CONTROLLERS, "InCorrect value for Strings::CONTROLLERS");
        $this->assertNotNull($controllers);
    }
    
    public function testConstFactories() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::FACTORIES.");
        $factories = Strings::FACTORIES;
        $eol = Strings::detectEol($factories, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::FACTORIES");
        $this->assertEquals($factories, Strings::FACTORIES, "InCorrect value for Strings::FACTORIES");
        $this->assertNotNull($factories);
    }
    
    public function testConstDispatch() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::DISPATCH.");
        $dispatch = Strings::DISPATCH;
        $eol = Strings::detectEol($dispatch, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::DISPATCH");
        $this->assertEquals($dispatch, Strings::DISPATCH, "InCorrect value for Strings::DISPATCH");
        $this->assertNotNull($dispatch);
    }
    
    public function testConstInvokables() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::INVOKABLES.");
        $invokables = Strings::INVOKABLES;
        $eol = Strings::detectEol($invokables, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::INVOKABLES");
        $this->assertEquals($invokables, Strings::INVOKABLES, "InCorrect value for Strings::INVOKABLES");
        $this->assertNotNull($invokables);
    }
    
    public function testConstRequest() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::REQUEST.");
        $request = Strings::REQUEST;
        $eol = Strings::detectEol($request, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::REQUEST");
        $this->assertEquals($request, Strings::REQUEST, "InCorrect value for Strings::REQUEST");
        $this->assertNotNull($request);
    }
    
    public function testConstResponse() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::RESPONSE.");
        $response = Strings::RESPONSE;
        $eol = Strings::detectEol($response, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::RESPONSE");
        $this->assertEquals($response, strval(Strings::RESPONSE), "InCorrect value for Strings::RESPONSE");
        $this->assertNotNull($response);
    }
    
    public function testConstRouter() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::ROUTER.");
        $router = Strings::ROUTER;
        $eol = Strings::detectEol($router, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::ROUTER");
        $this->assertEquals($router, Strings::ROUTER, "InCorrect value for Strings::ROUTER");
        $this->assertNotNull($router);
    }
    
    public function testConstController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::CONTROLLER.");
        $controller = Strings::CONTROLLER;
        $eol = Strings::detectEol($controller, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::CONTROLLER");
        $this->assertEquals($controller, Strings::CONTROLLER, "InCorrect value for Strings::CONTROLLER");
        $this->assertNotNull($controller);
    }
    
    public function testConstAction() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::ACTION.");
        $action = Strings::ACTION;
        $eol = Strings::detectEol($action, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::ACTION");
        $this->assertEquals($action, Strings::ACTION, "InCorrect value for Strings::ACTION");
        $this->assertNotNull($action);
    }
    
    public function testConstLoginController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::LOGIN_CONTROLLER.");
        $loginController = Strings::LOGIN_CONTROLLER;
        $eol = Strings::detectEol($loginController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::LOGIN_CONTROLLER");
        $this->assertEquals($loginController, Strings::LOGIN_CONTROLLER, "InCorrect value for Strings::LOGIN_CONTROLLER");
        $this->assertNotNull($loginController);
    }
    
    public function testConstSuccessController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SUCCESS_CONTROLLER.");
        $successController = Strings::SUCCESS_CONTROLLER;
        $eol = Strings::detectEol($successController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SUCCESS_CONTROLLER");
        $this->assertEquals($successController, Strings::SUCCESS_CONTROLLER, "InCorrect value for Strings::SUCCESS_CONTROLLER");
        $this->assertNotNull($successController);
    }
    
    public function testConstMyAuthStorage() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::MY_AUTH_STORAGE.");
        $myAuthStorage = Strings::MY_AUTH_STORAGE;
        $eol = Strings::detectEol($myAuthStorage, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::MY_AUTH_STORAGE");
        $this->assertEquals($myAuthStorage, Strings::MY_AUTH_STORAGE, "InCorrect value for Strings::MY_AUTH_STORAGE");
        $this->assertNotNull($myAuthStorage);
    }
    
    public function testConstMyAuthService() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::AUTH_SERVICE.");
        $authService = Strings::AUTH_SERVICE;
        $eol = Strings::detectEol($authService, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::AUTH_SERVICE");
        $this->assertEquals($authService, Strings::AUTH_SERVICE, "InCorrect value for Strings::AUTH_SERVICE");
        $this->assertNotNull($authService);
    }
    
    public function testConstZendDBAdabter() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::ZEND_DB_ADAPTER.");
        $zendDBAdapter = Strings::ZEND_DB_ADAPTER;
        $eol = Strings::detectEol($zendDBAdapter, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::ZEND_DB_ADAPTER");
        $this->assertEquals($zendDBAdapter, Strings::ZEND_DB_ADAPTER, "InCorrect value for Strings::ZEND_DB_ADAPTER");
        $this->assertNotNull($zendDBAdapter);
    }
    
    public function testConstAbstractActionController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::ABSTRACT_ACTION_CONTROLLER.");
        $abstractActionController = Strings::ABSTRACT_ACTION_CONTROLLER;
        $eol = Strings::detectEol($abstractActionController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::ABSTRACT_ACTION_CONTROLLER");
        $this->assertEquals($abstractActionController, Strings::ABSTRACT_ACTION_CONTROLLER, "InCorrect value for Strings::ABSTRACT_ACTION_CONTROLLER");
        $this->assertNotNull($abstractActionController);
    }
    
    public function testConstClassmapAutoloader() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::CLASS_MAP_AUTO_LOADER.");
        $classmapAutoloader = Strings::CLASS_MAP_AUTO_LOADER;
        $eol = Strings::detectEol($classmapAutoloader, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::CLASS_MAP_AUTO_LOADER");
        $this->assertEquals($classmapAutoloader, Strings::CLASS_MAP_AUTO_LOADER, "InCorrect value for Strings::CLASS_MAP_AUTO_LOADER");
        $this->assertNotNull($classmapAutoloader);
    }
    
    public function testConstStandardAutoloader() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::STANDARD_AUTO_LOADER.");
        $standardAutoloader = Strings::STANDARD_AUTO_LOADER;
        $eol = Strings::detectEol($standardAutoloader, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::STANDARD_AUTO_LOADER");
        $this->assertEquals($standardAutoloader, Strings::STANDARD_AUTO_LOADER, "InCorrect value for Strings::STANDARD_AUTO_LOADER");
        $this->assertNotNull($standardAutoloader);
    }
    
    public function testConstSalesItemsControllerFactory() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_ITEMS_CONTROLLER_FACTORY.");
        $salesItemsControllerFactory = Strings::SALES_ITEMS_CONTROLLER_FACTORY;
        $eol = Strings::detectEol($salesItemsControllerFactory, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_ITEMS_CONTROLLER_FACTORY");
        $this->assertEquals($salesItemsControllerFactory, Strings::SALES_ITEMS_CONTROLLER_FACTORY, "InCorrect value for Strings::SALES_ITEMS_CONTROLLER_FACTORY");
        $this->assertNotNull($salesItemsControllerFactory);
    }
    
    public function testConstSalesSalesControllerFactory() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_SALES_CONTROLLER_FACTORY.");
        $salesSalesControllerFactory = Strings::SALES_SALES_CONTROLLER_FACTORY;
        $eol = Strings::detectEol($salesSalesControllerFactory, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_SALES_CONTROLLER_FACTORY");
        $this->assertEquals($salesSalesControllerFactory, Strings::SALES_SALES_CONTROLLER_FACTORY, "InCorrect value for Strings::SALES_SALES_CONTROLLER_FACTORY");
        $this->assertNotNull($salesSalesControllerFactory);
    }
    
    public function testConstSalesUsersControllerFactory() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_USERS_CONTROLLER_FACTORY.");
        $salesUsersControllerFactory = Strings::SALES_USERS_CONTROLLER_FACTORY;
        $eol = Strings::detectEol($salesUsersControllerFactory, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_USERS_CONTROLLER_FACTORY");
        $this->assertEquals($salesUsersControllerFactory, Strings::SALES_USERS_CONTROLLER_FACTORY, "InCorrect value for Strings::SALES_USERS_CONTROLLER_FACTORY");
        $this->assertNotNull($salesUsersControllerFactory);
    }
    
    public function testConstSalesItemsController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_ITEMS_CONTROLLER.");
        $salesItemsController = Strings::SALES_ITEMS_CONTROLLER;
        $eol = Strings::detectEol($salesItemsController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_ITEMS_CONTROLLER");
        $this->assertEquals($salesItemsController, Strings::SALES_ITEMS_CONTROLLER, "InCorrect value for Strings::SALES_ITEMS_CONTROLLER");
        $this->assertNotNull($salesItemsController);
    }
    
    public function testConstSalesSalesController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_SALES_CONTROLLER.");
        $salesSalesController = Strings::SALES_SALES_CONTROLLER;
        $eol = Strings::detectEol($salesSalesController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_SALES_CONTROLLER");
        $this->assertEquals($salesSalesController, Strings::SALES_SALES_CONTROLLER, "InCorrect value for Strings::SALES_SALES_CONTROLLER");
        $this->assertNotNull($salesSalesController);
    }
    
    public function testConstSalesUsersController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::SALES_USERS_CONTROLLER.");
        $salesUsersController = Strings::SALES_USERS_CONTROLLER;
        $eol = Strings::detectEol($salesUsersController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::SALES_USERS_CONTROLLER");
        $this->assertEquals($salesUsersController, Strings::SALES_USERS_CONTROLLER, "InCorrect value for Strings::SALES_USERS_CONTROLLER");
        $this->assertNotNull($salesUsersController);
    }
    
    public function testConstAjaxItemsController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::AJAX_ITEMS_CONTROLLER.");
        $ajaxItemsController = Strings::AJAX_ITEMS_CONTROLLER;
        $eol = Strings::detectEol($ajaxItemsController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::AJAX_ITEMS_CONTROLLER");
        $this->assertEquals($ajaxItemsController, Strings::AJAX_ITEMS_CONTROLLER, "InCorrect value for Strings::AJAX_ITEMS_CONTROLLER");
        $this->assertNotNull($ajaxItemsController);
    }
    
    public function testConstAjaxSalesController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::AJAX_SALES_CONTROLLER.");
        $ajaxSalesController = Strings::AJAX_SALES_CONTROLLER;
        $eol = Strings::detectEol($ajaxSalesController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::AJAX_SALES_CONTROLLER");
        $this->assertEquals($ajaxSalesController, Strings::AJAX_SALES_CONTROLLER, "InCorrect value for Strings::AJAX_SALES_CONTROLLER");
        $this->assertNotNull($ajaxSalesController);
    }
    
    public function testConstAjaxUsersController() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::AJAX_USERS_CONTROLLER.");
        $ajaxUsersController = Strings::AJAX_USERS_CONTROLLER;
        $eol = Strings::detectEol($ajaxUsersController, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::AJAX_USERS_CONTROLLER");
        $this->assertEquals($ajaxUsersController, Strings::AJAX_USERS_CONTROLLER, "InCorrect value for Strings::AJAX_USERS_CONTROLLER");
        $this->assertNotNull($ajaxUsersController);
    }
    
    public function testConstItemsFilterTableArrayService() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::ITEMS_FILTER_TABLE_ARRAY_SERVICE.");
        $itemsFilterTableArrayService = Strings::ITEMS_FILTER_TABLE_ARRAY_SERVICE;
        $eol = Strings::detectEol($itemsFilterTableArrayService, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::ITEMS_FILTER_TABLE_ARRAY_SERVICE");
        $this->assertEquals($itemsFilterTableArrayService, Strings::ITEMS_FILTER_TABLE_ARRAY_SERVICE, "InCorrect value for Strings::ITEMS_FILTER_TABLE_ARRAY_SERVICE");
        $this->assertNotNull($itemsFilterTableArrayService);
    }
    
    public function testConstRequiredMarkInFormLabel() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::REQUIRED_MARK_IN_FORM_LABEL.");
        $requiredMarkInformLabel = Strings::REQUIRED_MARK_IN_FORM_LABEL;
        $eol = Strings::detectEol($requiredMarkInformLabel, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::REQUIRED_MARK_IN_FORM_LABEL");
        $this->assertEquals($requiredMarkInformLabel, Strings::REQUIRED_MARK_IN_FORM_LABEL, "InCorrect value for Strings::REQUIRED_MARK_IN_FORM_LABEL");
        $this->assertNotNull($requiredMarkInformLabel);
    }
    
    public function testConstFormLabel() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::FORM_LABEL.");
        $formLabel = Strings::FORM_LABEL;
        $eol = Strings::detectEol($formLabel, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::FORM_LABEL");
        $this->assertEquals($formLabel, Strings::FORM_LABEL, "InCorrect value for Strings::FORM_LABEL");
        $this->assertNotNull($formLabel);
    }
    
    public function testConstNamespaces() {
        Logger::stderr(StringsTest::class, __LINE__, "Testing Strings::NAMESPACES.");
        $namespaces = Strings::NAMESPACES;
        $eol = Strings::detectEol($namespaces, PHP_EOL);
        $this->assertEquals(PHP_EOL, $eol, "InCorrect EOL for string StringsTest::NAMESPACES");
        $this->assertEquals($namespaces, Strings::NAMESPACES, "InCorrect value for Strings::NAMESPACES");
        $this->assertNotNull($namespaces);
    }
    
}
