<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Login\Controller;

use Application\Service\LoggingService;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class SuccessController extends AbstractActionController
{
    
    protected $authservice;
    
    protected $logger;
    
    public function __construct(AuthenticationService $authservice, LoggingService $logger) {
        $this->authservice = $authservice;
        $this->logger = $logger;
    }
    
    public function indexAction()
    {
        if (! $this->authservice->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
         
        return new ViewModel();
    }
}
