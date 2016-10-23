<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

/**
 * Description of RestService
 *
 * @author jasonpalmer
 */
class RestService implements RestServiceInterface {
    
    protected $logger;
    
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
        $request->setPost(new Parameters($params));

        $client = new Client();
        $response = $client->dispatch($request);
        return json_decode($response->getBody(), true);
    }
    
    public function setLogger($logger) {
        $this->logger = $logger;
    }



}
