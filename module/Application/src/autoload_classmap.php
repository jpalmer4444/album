<?php

namespace Application;

/* 
 * Speeds up web-app Classloading dramatically.
 */
return array(
    "Application\Controller\IndexController" =>  __DIR__ . "/Controller/IndexController.php",
    "Application\Service\FFMEntityManagerService" =>  __DIR__ . "/Service/FFMEntityManagerService.php",
    "Application\Service\LoggingService" =>  __DIR__ . "/Service/LoggingService.php",
    "Application\Service\ReportService" =>  __DIR__ . "/Service/ReportService.php",
    "Application\Service\RestService" =>  __DIR__ . "/Service/RestService.php",
    "Application\Service\SessionManager" =>  __DIR__ . "/Service/SessionManagerFactory.php",
    "Application\Factory\FFMEntityManagerServiceFactory" =>  __DIR__ . "/Factory/FFMEntityManagerServiceFactory.php",
    "Application\Factory\LoggingServiceFactory" =>  __DIR__ . "/Factory/LoggingServiceFactory.php",
    "Application\Factory\ReportServiceFactory" =>  __DIR__ . "/Factory/ReportServiceFactory.php",
    "Application\Factory\RestServiceFactory" =>  __DIR__ . "/Factory/RestServiceFactory.php",
    "Application\Factory\SessionManagerFactory" =>  __DIR__ . "/Factory/SessionManagerFactory.php",
    "Application\Utility\DateUtils" =>  __DIR__ . "/Utility/DateUtils.php",
    "Application\Utility\Logger" =>  __DIR__ . "/Utility/Logger.php",
    "Application\Utility\Strings" =>  __DIR__ . "/Utility/Strings.php",
    "Application\ViewHelper\RequiredMarkInFormLabel" =>  __DIR__ . "/ViewHelper/RequiredMarkInFormLabel.php"
);

