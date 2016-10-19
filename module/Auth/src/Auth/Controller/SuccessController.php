<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Log\Writer\Stream;
 
class SuccessController extends AbstractActionController
{
    
    protected $authservice;
    
    protected $logger;
    
    public function __construct(AuthenticationService $authservice) {
        $this->authservice = $authservice;
        $writer = new Stream('php://stderr');
        $logger = new Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $this->logger->info('SuccessController instantiated.');
    }
    
    public function indexAction()
    {
        if (! $this->authservice->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
         
        return new ViewModel();
    }
}
