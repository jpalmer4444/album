<?php
/**
 */

namespace Sales\Controller;

use Application\Service\LoggingService;
use Application\Service\RestServiceInterface;
use Login\Model\MyAuthStorage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ItemsController extends AbstractActionController
{
    
    const ID = "jpalmer";
    const PASSWORD = "goodbass";
    const OBJECT = "items";
    
    protected $restService;

    protected $logger;
    
    protected $myauthstorage;
    
    public function __construct(RestServiceInterface $restService, LoggingService $logger, MyAuthStorage $myauthstorage){
        $this->restService = $restService;
        $this->logger = $logger;
        $this->myauthstorage = $myauthstorage;
    }
    
    public function indexAction()
    {
        return new ViewModel(array(
             
         ));
    }
    
    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }
    
}
