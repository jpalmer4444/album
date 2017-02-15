<?php

namespace Application\Service;

use Application\Utility\Logger;
use Exception;
use Login\Model\MyAuthStorage;
use Predis\Client;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of LoggingService
 *
 * @author jasonpalmer
 */
class PredisService  {

    protected $client;
    protected $container;
    protected $sessionId;

    public function __construct(ServiceManager $container) {
        try{
            session_start();
            $this->sessionId = session_id();
            Logger::info("PredisService", __LINE__, 'Generated session_id: ' . $this->sessionId);
        } catch (Exception $exception){
            $msg = 'Cannot generate session_id assuming the environment is testing.';
            Logger::stderr("PredisService", __LINE__, $msg);
            Logger::info("PredisService", __LINE__, $msg);
            $this->sessionId = '__testing__';
        }
        $parameters = [
            "host" => $container->get('config')['redis_config']['host'],
            "port" => $container->get('config')['redis_config']['port'],
            "ttl" => $container->get('config')['redis_config']['ttl']
        ];
        $options = [
            'parameters' => [
                'database' => $container->get('config')['redis_config']['database'],
            ],
        ];
        $client = new Client($parameters, $options);
        $this->client = $client;
        if (empty($this->get($this->sessionId))) {
            $storage = new MyAuthStorage("zf_tutorial");
            $serializedData = $this->serialize($storage->toArray());
            Logger::info("PredisService", __LINE__, 'Serialized: ' . $serializedData);
            $this->set($this->sessionId, $serializedData);
        }
    }

    public function getMyAuthStorage() {
        $myauthstoragearray = unserialize($this->get($this->sessionId));
        //Logger::info("PredisService", __LINE__, 'UnSerialized: ' . strval($myauthstorage));
        return MyAuthStorage::getInstance($myauthstoragearray);
    }

    public function setMyAuthStorage(MyAuthStorage $myauthstorage) {
        $serializedData = $this->serialize($myauthstorage->toArray());
        $this->set($this->sessionId, $serializedData);
    }
    
    public function clear() {
        $this->client->del($this->sessionId);
    }

    public function get($key) {
        return $this->client->get($key);
    }

    public function set($key, $value) {
        return $this->client->set($key, $value);
    }

    public function unserialize($data) {
        return unserialize($data);
    }

    public function serialize($clazz) {
        return serialize($clazz);
    }

}
