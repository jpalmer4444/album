<?php

namespace Command\Controller\Reporting;

use Application\Utility\Logger;
use Predis\Client;
use RuntimeException;
use Zend\Console\Console;
use Zend\Console\Request;
use Zend\Di\ServiceLocatorInterface;
use Zend\Mvc\Controller\AbstractActionController;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RedisCommandController extends AbstractActionController {

    protected $container;

    public function __construct(ServiceLocatorInterface $sm) {
        Logger::info("RedisCommandController", __LINE__, "Redis Commands.");
        $this->container = $sm;
    }

    protected function rediscommandAction() {

        Logger::info("RedisCommandController", __LINE__, "Redis Commands.");

        $request = $this->getRequest();

        $console = Console::getInstance();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof Request) {
            //Logger::info("PriceOverrideController", __LINE__, get_class($request));
            throw new RuntimeException('You can only use this action from a console!');
        } else {
            $console->clear();
        }

        // Get user email from console and check if the user used --verbose or -v flag
        $verbose = $request->getParam('verbose') || $request->getParam('v');

        if ($verbose) {
            $console->writeLine("<-v|--verbose> Output Enabled.");
        }

        $host = $request->getParam('host') || $request->getParam('h');
        $port = $request->getParam('port') || $request->getParam('p');
        $database = $request->getParam('database') || $request->getParam('d');

        $msgHost;
        if (empty($host)) {
            $host = $this->container->get('config')['redis_config']['host'];
            $msgHost = $verbose ? "Using Web-application Default Redis Host: " . $host : 'Host: ' . $host;
        } else {
            $msgHost = $verbose ? "Using Redis Host: " . $host : 'Host: ' . $host;
        }
        
        Logger::info("RedisCommandController", __LINE__, $msgHost);

        $msgPort;
        if (empty($port)) {
            $port = $this->container->get('config')['redis_config']['port'];
        $msgPort = $verbose ? "Using Web-application Default Redis Port: " . $port : 'Port: ' . $port;
        } else {
            $msgPort = $verbose ? "Using Redis Port: " . $port : 'Port: ' . $port;
        }
        
        Logger::info("RedisCommandController", __LINE__, $msgPort);

        $msgDatabase;
        if (empty($database)) {
            $database = $this->container->get('config')['redis_config']['database'];
        $msgDatabase = $verbose ? "Using Web-application Default Redis Database: " . $database : 'Database: ' . $database;
        } else {
            $msgDatabase = $verbose ? "Using Redis Database: " . $database : 'Database: ' . $database;
        }
        
        Logger::info("RedisCommandController", __LINE__, $msgDatabase);

        $cmd = $this->notNull($cmd);

        switch ($cmd) {

            case 'connect' :

            default:
                $this->connectToRedis($host, $port, $database);
                break;
        }
    }
    
    private function notNull($testable){
        return !empty($testable) ? $testable : '';
    }
    

    private function connectToRedis($host, $port, $database) {
        $parameters = [
            "host" => $this->container->get('config')['redis_config']['host'],
            "port" => $this->container->get('config')['redis_config']['port'],
            "ttl" => '86400'
        ];
        $options = [
            'parameters' => [
                'database' => $this->container->get('config')['redis_config']['database'],
            ],
        ];
        $client = new Client($parameters, $options);
        Logger::info("RedisCommandController", __LINE__, 'Connected to Redis');
    }

}
