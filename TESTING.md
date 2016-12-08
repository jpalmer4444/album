To test server side php:
Unit test configuration for PHPUnit can be found under pricing/module/Sales/test and can be run using command:
cd ${PROJECT_DIRECTORY}/module/Sales/test;phpunit 
OR
phpunit /u/local/jasonpalmer/pricing/module/Sales/test (runs all phpunit server side tests)

To test client side javascript navigate to:
https://pricing.localhost/clientunittests/items.html
The tests are run automatically when you browse to this page in your Browser.