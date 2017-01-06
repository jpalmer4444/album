<?php

/*
 * Handles login
 */

namespace Login\Controller;

require 'vendor/autoload.php';

use Application\Service\FFMEntityManagerService;
use Application\Service\LoggingService;
use Application\Utility\Logger;
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
    protected $entityManager;

    public function __construct(AuthenticationService $authservice, MyAuthStorage $storage, LoggingService $logger, FFMEntityManagerService $entityManager) {
        $this->authservice = $authservice;
        $this->storage = $storage;
        $this->form = $this->getForm();
        $this->logger = $logger;
        $this->entityManager = $entityManager->getEntityManager();
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
        Logger::info("LoginController", __LINE__, 'Checking for identity');
        if ($this->getAuthService()->hasIdentity()) {
            Logger::info("LoginController", __LINE__, 'Identity found.');
            if($this->getSessionStorage()->admin()){
                return $this->redirect()->toRoute('sales');
            }else{
                return $this->redirect()->toRoute('users');
            }
        }else{
            Logger::info("LoginController", __LINE__, 'Identity not found.');
        }
        $form = $this->getForm();
        return array(
            'form' => $form,
            'messages' => $this->plugin('flashmessenger')->getMessages()
        );
    }

    public function authenticateAction() {
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
                    $this->plugin('flashmessenger')->addMessage($message);
                }
                if ($result->isValid()) {
                    //set roles
                    
                    $user = $this->entityManager->find('DataAccess\FFM\Entity\User', $request->getPost('username'));
                    Logger::info("LoginController", __LINE__, 'Username: ' . $user->getUsername());
                    $roleXrefObjs = $this->entityManager->getRepository('DataAccess\FFM\Entity\UserRoleXref')->findBy(array('username' => $request->getPost('username')));
                    $roles = [];
                    $idx = 0;
                    foreach ($roleXrefObjs as $role) {
                        //save message temporary into flashmessenger
                        //Logger::info(static::class, __LINE__, 'Role: ' . $role->getRole());
                        $roles[$idx++] = $role;
                    }
                    $this->getSessionStorage()->addRoles($roles);
                    $this->getSessionStorage()->addUser($user);//set $user in session storage for later access.
                    //if user is admin user then set salespersonInPlay to the user
                    $redirect = $this->getSessionStorage()->admin() ? 'sales' : 'users';
                    
                    //check if it has rememberMe :
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    if ($request->getPost('rememberme') == 1) {
                        $this->getSessionStorage()
                                ->setRememberMe(1);
                        //set storage again 
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }else{
               Logger::info("LoginController", __LINE__, 'attempting AuthenticationAction.'); 
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
