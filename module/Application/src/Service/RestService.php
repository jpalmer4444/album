<?php

namespace Application\Service;

use Application\Service\RestServiceInterface;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;
use Zend\Http\Request;

/**
 * Description of RestService
 *
 * @author jasonpalmer
 */
class RestService implements RestServiceInterface {
    
    protected $logger;
    
    public function __construct(LoggingService $logger){
        $this->logger = $logger;
    }
    
    /**
     * 
     * @param type $url URL
     * @param type $method Method to use.
     * @param type $params Parameters to add
     * @return type
     */
    public function rest($url, $method = 'GET', $params = []) {
        
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));
        $request->setUri($url);
        $request->setMethod($method);
        
        if(strcmp(strtoupper($method), "GET") == 0){
            $request->setQuery(new Parameters($params));
        }else{
            $request->setPost(new Parameters($params));
        }

        $client = new Client();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');
        
        $response = $client->dispatch($request);
        return json_decode($response->getBody(), true);
    }
    
    public function setLogger($logger) {
        $this->logger = $logger;
    }



}
