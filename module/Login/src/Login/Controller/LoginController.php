<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Login\Controller;

require 'vendor/autoload.php';

use Application\Service\LoggingService;
use Login\Model\MyAuthStorage;
use Login\Model\User;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController {

    protected $form;
    protected $storage;
    protected $flashmessages;
    protected $authservice;
    public $value;
    protected $logger;

    public function __construct(AuthenticationService $authservice, MyAuthStorage $storage, LoggingService $logger) {
        $this->authservice = $authservice;
        $this->storage = $storage;
        $this->form = $this->getForm();
        $this->logger = $logger;
    }

    public function getAuthService() {

        return $this->authservice;
    }

    public function getSessionStorage() {

        return $this->storage;
    }

    public function getForm() {
        if (!$this->form) {
            $this->form = new User();
        }

        return $this->form;
    }

    public function loginAction() {
        //if already login, redirect to success page 
        $this->logger->info('Checking for identity');
        if ($this->getAuthService()->hasIdentity()) {
            $this->logger->info('Identity found.');
            return $this->redirect()->toRoute('success');
        }else{
            $this->logger->info('Identity not found.');
        }

        $form = $this->getForm();

        return array(
            'form' => $form,
            'messages' => $this->plugin('flashmessenger')->getMessages()
        );
    }

    public function authenticateAction() {
        
        $this->logger->info('AuthenticationAction called.');
        
        $form = $this->getForm();
        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //check authentication...
                $this->getAuthService()->getAdapter()
                        ->setIdentity($request->getPost('username'))
                        ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    $this->plugin('flashmessenger')->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1) {
                        $this->getSessionStorage()
                                ->setRememberMe(1);
                        //set storage again 
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }else{
               $this->logger->info('attempting AuthenticationAction.'); 
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction() {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->plugin('flashmessenger')->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
