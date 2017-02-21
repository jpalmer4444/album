<?php

/*
 * Handles login
 */

namespace Login\Controller;

require 'vendor/autoload.php';

use Application\Service\EntityService;
use Application\Service\LoggingService;
use Application\Service\SessionService;
use Application\Utility\Logger;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRoleXrefRepositoryImpl;
use Login\Model\User;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\SessionManager;

class LoginController extends AbstractActionController {

    protected $form;
    protected $storage;
    protected $flashmessages;
    protected $authservice;
    protected $userrepository;
    protected $userrolexrefrepository;
    public $value;
    protected $logger;
    protected $entityManager;
    protected $sessionManager;
    protected $sessionService;

    public function __construct(AuthenticationService $authservice, LoggingService $logger, EntityService $entityManager, SessionService $sessionService, SessionManager $sessionManager, UserRepositoryImpl $userrepository, UserRoleXrefRepositoryImpl $userrolexrefrepository) {
        $this->authservice = $authservice;
        $this->sessionManager = $sessionManager;
        $this->form = $this->getForm();
        $this->logger = $logger;
        $this->entityManager = $entityManager->getEntityManager();
        $this->sessionService = $sessionService;
        $this->userrepository = $userrepository;
        $this->userrolexrefrepository = $userrolexrefrepository;
    }

    public function getAuthService() {

        return $this->authservice;
    }

    public function getForm() {
        if (!$this->form) {
            $this->form = new User();
        }

        return $this->form;
    }

    public function loginAction() {

        //if already login, redirect to success page 
        Logger::info("LoginController", __LINE__, 'Checking for identity');
        if ($this->getAuthService()->hasIdentity()) {
            Logger::info("LoginController", __LINE__, 'Identity found.');
            //if ($this->sessionService->admin()) {
            //return $this->redirect()->toRoute('sales');
            //} else {
            //return $this->redirect()->toRoute('users');
            //}
        } else {
            Logger::info("LoginController", __LINE__, 'Identity not found.');
        }
        $form = $this->getForm();
        return array(
            'form' => $form,
            'messages' => $this->plugin('flashmessenger')->getMessages()
        );
    }

    public function authenticateAction() {
        $this->getAuthService()->clearIdentity();
        Logger::info("LoginController", __LINE__, 'AuthenticationAction called.');
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
                    if(strcmp($message, "Authentication successful.") != 0){
                        $this->plugin('flashmessenger')->addMessage($message);
                    }
                }
                if ($result->isValid()) {
                    //set roles

                    $this->sessionService->login($request->getPost('username'));
                    //if user is admin user then set salespersonInPlay to the user
                    $redirect = $this->sessionService->admin() ? 'sales' : 'users';

                    //check if it has rememberMe :
                    //$this->getAuthService()->setStorage($this->getSessionStorage());
                    if ($request->getPost('rememberme') == 1) {
                        $this->authservice->getStorage()
                                ->setRememberMe(1);
                        //set storage again 
                    }
                } else {
                    $this->explainLoginFailure($request->getPost('username'), 'auth.result');
                    //Logger::info("LoginController", __LINE__, 'Login RESULT failed! Cannot login username: ' . $request->getPost('username'));
                }
            } else {
                $this->explainLoginFailure($request->getPost('username'), 'form');
            }
        }
        return $this->redirect()->toRoute($redirect);
    }

    private function explainLoginFailure($username, $reason) {
        if (!empty($username)) {
            $user = $this->userrepository->findUser($username);
            $roleXrefs = $this->userrolexrefrepository->findBy(array('username' => $username));
            Logger::info("LoginController.EXPLAIN_LOGIN_FAILURE($reason)", __LINE__, "User: " . var_export($user, TRUE));
            Logger::info("LoginController.EXPLAIN_LOGIN_FAILURE($reason)", __LINE__, "Roles: " . var_export($roleXrefs, TRUE));
        } else {
            Logger::info("LoginController.EXPLAIN_LOGIN_FAILURE($reason)", __LINE__, "No Username!");
        }
    }

    public function logoutAction() {
        $this->getAuthService()->clearIdentity();
        $this->sessionService->logout();
        $this->plugin('flashmessenger')->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
