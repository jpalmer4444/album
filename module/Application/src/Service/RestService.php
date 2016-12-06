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
    
    protected $pricingconfig;

    public function __construct($sm) {
        $this->logger = $sm->get('LoggingService');
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
        $request->setUri($url);
        $request->setMethod($method);

        if (strcmp(strtoupper($method), "GET") == 0) {
            $request->setQuery(new Parameters($params));
        } else {
            $request->setPost(new Parameters($params));
        }

        $client = new Client();

        $options = array(
            'ssl' => $this->pricingconfig['ssl'],
        );
        
        //support both .crt and .key certs OR .pem cert
        if(array_key_exists("sslcert", $this->pricingconfig)){
            $options['sslcert'] = $this->pricingconfig['sslcert'];
        }else{
            if(array_key_exists("sslcapath", $this->pricingconfig)){
                $options['sslcapath'] = $this->pricingconfig['sslcapath'];
            }
            //$options['sslcapath'] = $this->pricingconfig['sslcapath'];
            if(array_key_exists("sslcafile", $this->pricingconfig)){
                $options['sslcafile'] = $this->pricingconfig['sslcafile'];
            }
        }

        $client->setOptions($options);

        $response = $client->dispatch($request);
        return json_decode($response->getBody(), true);
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

}
