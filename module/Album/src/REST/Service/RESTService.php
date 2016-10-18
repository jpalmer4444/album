<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace REST\Service;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

/**
 * Description of RESTService
 *
 * @author jasonpalmer
 */
class RESTService implements RESTServiceInterface {

    //put your code here
    public function findUserList($userId) {
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));
        $someurl = 'http:';
        $request->setUri($someurl);
        $request->setMethod('POST');
        $request->setPost(new Parameters(array('someparam' => $somevalue)));

        $client = new Client();
        $response = $client->dispatch($request);
        $data = json_decode($response->getBody(), true);
    }

}
