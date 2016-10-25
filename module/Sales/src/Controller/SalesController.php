<?php

namespace Sales\Controller;

use Application\Service\LoggingService;
use Application\Service\RestServiceInterface;
use Login\Model\MyAuthStorage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SalesController extends AbstractActionController{
    
    protected $restService;

    protected $logger;
    
    //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=salespeople
    
    const ID = "jpalmer";
    const PASSWORD = "goodbass";
    const OBJECT = "salespeople";
    protected $myauthstorage;
    
    public function __construct(RestServiceInterface $restService, LoggingService $logger, MyAuthStorage $myauthstorage){
        $this->restService = $restService;
        $this->logger = $logger;
        $this->myauthstorage = $myauthstorage;
    }

    public function indexAction() {
        $this->logger->info('Retrieving ' . SalesController::OBJECT . '.');
        $params = [
            "id" => SalesController::ID,
            "pw" => SalesController::PASSWORD,
            "object" => SalesController::OBJECT
        ];
        $url = \Application\Module::BYSKU_URL;
        $method = \Application\Module::BYSKU_METHOD;
        
        $json = $this->rest($url, $method, $params);
        $this->logger->info('Retrieved #' . count($json[SalesController::OBJECT]) . ' ' . SalesController::OBJECT . '.');
        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
