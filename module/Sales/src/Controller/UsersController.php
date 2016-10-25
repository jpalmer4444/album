<?php
/**
 */

namespace Sales\Controller;

use Application\Service\LoggingService;
use Application\Service\RestServiceInterface;
use Login\Model\MyAuthStorage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{
    const ID = "jpalmer";
    const PASSWORD = "goodbass";
    const OBJECT = "customers";
    
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
        //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=customers&salespersonid=183
        
        $requestedsalespersonid = $this->params()->fromQuery('salespersonid');
        $userroles = $this->myauthstorage->getRoles();
        
        if(isset($requestedsalespersonid) && $this->myauthstorage->admin()){
            $salespersonid = $requestedsalespersonid;
        }else{
            $salespersonid = $this->myauthstorage->getUser()->getSales_attr_id();
        }
        
        $this->logger->info('Retrieving Salespeople. ID: ' . $salespersonid);
        $params = [
            "id" => UsersController::ID,
            "pw" => UsersController::PASSWORD,
            "object" => UsersController::OBJECT,
            "salespersonid" => $salespersonid
        ];
        
        $url = \Application\Module::BYSKU_URL;
        $method = \Application\Module::BYSKU_METHOD;
        
        $json = $this->rest($url, $method, $params);
        $this->logger->info('Retrieved #' . count($json) . ' ' . UsersController::OBJECT . '.');
        //$this->logger->debug($json);
        return new ViewModel(array(
            "json" => $json
        ));
    }
    
    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }
}
