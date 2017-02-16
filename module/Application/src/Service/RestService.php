<?php

namespace Application\Service;

use Application\Utility\Logger;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

/**
 * Description of RestService
 *
 * @author jasonpalmer
 */
class RestService  {

    protected $pricingconfig;

    public function __construct($sm) {
        $this->pricingconfig = $sm->get('config')['pricing_config'];
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
        Logger::info("RestService", __LINE__, "Calling WebService with GET URL: " . $url . "?" . implode("&",$params));
        $request->setUri($url);
        $request->setMethod($method);
        if (strcmp(strtoupper($method), "GET") == 0) {
            $request->setQuery(new Parameters($params));
        } else {
            $request->setPost(new Parameters($params));
        }
        $client = new Client();
        $adapter = new Curl();
        $adapter->setOptions(array(
            'curloptions' => array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
            )
        ));
        $client->setAdapter($adapter);
        $response = $client->dispatch($request);
        return json_decode($response->getBody(), true);
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

}
