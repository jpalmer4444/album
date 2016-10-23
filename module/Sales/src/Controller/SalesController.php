<?php

namespace Sales\Controller;

use Application\Service\RestServiceInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SalesController extends AbstractActionController{
    
    protected $restService;

    protected $logger;
    
    //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=salespeople
    
    protected $id = "jpalmer";
    protected $pw = "goodbass";
    protected $object = "salespeople";
    
    public function __construct(RestServiceInterface $restService){
        $this->restService = $restService;
        $writer = new Stream('php://stderr');
        $logger = new Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $this->logger->info('SalesController instantiated.');
    }

    public function indexAction() {
        $this->logger->info('Retrieving Salespeople.');
        $params = [
            "id" => $this->id,
            "pw" => $this->pw,
            "object" => $this->object
        ];
        $url = "http://svc.ffmalpha.com/bySKU.php";
        $method = "GET";
        $json = $this->rest($url, $method, $params);
        $this->logger->info('Retrieved Salespeople. #' . count($json));
        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
