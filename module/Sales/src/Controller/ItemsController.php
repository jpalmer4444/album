<?php
/**
 */

namespace Sales\Controller;

use Application\Service\RestServiceInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ItemsController extends AbstractActionController
{
    
    protected $restService;

    protected $logger;
    
    public function __construct(RestServiceInterface $restService){
        $this->restService = $restService;
        $writer = new Stream('php://stderr');
        $logger = new Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $this->logger->info('ItemsController instantiated.');
    }
    
    public function indexAction()
    {
        return new ViewModel(array(
             
         ));
    }
}
