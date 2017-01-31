<?php

namespace Application\Service;

use Login\Model\MyAuthStorage;
use Predis\Client;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of LoggingService
 *
 * @author jasonpalmer
 */
class PredisService implements PredisServiceInterface {
    
    protected $client;
    protected $container;
    
    public function __construct(ServiceManager $container) {
        $parameters = [ 
            "host"=>$container->get('config')['redis_config']['host'], 
            "port"=>$container->get('config')['redis_config']['port'], 
            "ttl"=>$container->get('config')['redis_config']['ttl']
            ];
        $options = [
            'parameters' => [
                'database' => $container->get('config')['redis_config']['database'],
            ],
        ];
        $client = new Client($parameters, $options);
        $this->client = $client;
        $this->container = $container;
    }
    
    public function getMyAuthStorage(){
        $storage = new MyAuthStorage("zf_tutorial");
        $storage->setLogger($this->container->get("LoggingService"));
        $myauthstorage = $this->unserialize($this->get("MyAuthStorage"));
        return $myauthstorage ? $myauthstorage : $storage;
    }
    
    public function setMyAuthStorage($myauthstorage){
        $this->set("MyAuthStorage", $this->serialize($myauthstorage));
    }
    
    
    public function get($key) {
        return $this->client->get($key);
    }
    
    public function set($key, $value) {
        return $this->client->mset($key, $value);
    }

    public function unserialize($data) {
        return unserialize($data);
    }

    public function serialize($clazz) {
        return serialize($clazz);
    }

}
