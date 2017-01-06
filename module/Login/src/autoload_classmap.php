<?php

namespace Login;

/* 
 * Speeds up web-app Classloading dramatically.
 */
return array(
    "Login\Controller\LoginController" =>  __DIR__ . "/Login/Controller/LoginController.php",
    "Login\Controller\SuccessController" =>  __DIR__ . "/Login/Controller/SuccessController.php",
    "Login\Factory\LoginControllerFactory" =>  __DIR__ . "/Login/Factory/LoginControllerFactory.php",
    "Login\Factory\SuccessControllerFactory" =>  __DIR__ . "/Login/Factory/SuccessControllerFactory.php",
    "Login\Model\MyAuthStorage" =>  __DIR__ . "/Login/Model/MyAuthStorage.php",
    "Login\Model\User" =>  __DIR__ . "/Login/Model/User.php"
);

