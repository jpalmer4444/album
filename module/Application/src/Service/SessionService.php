<?php

/*
 * Manages Session Data.
 */

namespace Application\Service;

use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRoleXrefRepositoryImpl;
use DataAccess\FFM\Entity\User;
use DataAccess\FFM\Entity\UserRoleXref;
use DateTime;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Session\SessionManager;

/**
 * Description of SessionService
 *
 * @author jasonpalmer
 */
class SessionService extends BaseService {

    protected $cookieService;

    /**
     * Session ID
     * @see session_id()
     * @var string
     */
    protected $sessionId;

    /**
     * UserRepository
     * @var UserRepositoryImpl
     */
    protected $userrepository;

    /**
     * UserRoleXrefRepository
     * @var UserRoleXrefRepositoryImpl
     */
    protected $userrolexrefrepository;

    /**
     * Roles
     * @var UserRoleXref
     */
    protected $roles;

    /**
     * SessionManager
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Salesperson In Play
     * @var User
     */
    protected $salespersoninplay;

    /**
     * User
     * @var User
     */
    protected $user;

    /**
     * Request
     * @var Request
     */
    protected $request;

    /**
     * Response
     * @var Response
     */
    protected $response;

    public function __construct(
            UserRepositoryImpl $userrepository, 
            UserRoleXrefRepositoryImpl $userrolexrefrepository, 
            SessionManager $sessionManager, 
            Request $request, 
            Response $response, 
            CookieService $cookieService) {
        $this->cookieService = $cookieService;
        $this->setUserRepository($userrepository);
        $this->setUserRoleXrefRepository($userrolexrefrepository);
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setSessionManager($sessionManager);
        $this->setSessionId($this->sessionManager->getId());
        if (!$this->sessionId) {
            if ($this->sessionManager->isValid()) {
                $this->sessionManager->start();
                $this->sessionId = $this->sessionManager->getId();
            }
        }
        $this->log(this::class, __LINE__, "(CONSTRUCTOR) Session Id: " . $this->sessionId);
        $this->doLookup();
    }

    public function login($username, $session_id = NULL) {
        try {
            $this->sessionId = !empty($session_id) ? $session_id : $this->sessionManager->getId();
            $this->log(this::class, __LINE__, "(LOGIN) Session Id: " . $this->sessionId);
            $this->log(this::class, __LINE__, "(LOGIN) username: " . $username);
            $user = $this->userrepository->findUser($username);
            $user->setSessionId($this->sessionId);
            $user->setLastLogin(new DateTime());
            $this->userrepository->mergeAndFlush($user);
            $this->user = $user;
            $this->log(this::class, __LINE__, "(LOGIN) Saved Session ID: " . $user->getSessionId());
            if (!empty($this->user)) {
                $roleXrefs = $this->userrolexrefrepository->findBy(array('username' => $this->user->getUsername()));
                $this->roles = [];
                $idx = 0;
                $this->log(this::class, __LINE__, "(LOGIN) Adding Roles: " . print_r($roleXrefs, TRUE));
                foreach ($roleXrefs as $role) {
                    $this->roles[$idx++] = $role;
                }
                $salespersoninplayusernamecookie = $this->cookieService->getCookie('salesperson', $this->request);
                $this->log(this::class, __LINE__, "(LOGIN) Looked up salesperson: " . (!empty($salespersoninplayusernamecookie) ? $salespersoninplayusernamecookie : "NULL"));
                if (!empty($salespersoninplayusernamecookie)) {
                    $this->salespersoninplay = $this->userrepository->findUser($salespersoninplayusernamecookie);
                }
            } else {
                $this->log(this::class, __LINE__, "Login Failed!");
            }
        } catch (\Exception $exc) {
            $this->log(this::class, __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    public function getSessionId() {
        return $this->sessionId;
    }

    public function logout() {
        try {
            $updateUser = $this->userrepository->findUser($this->user->getUsername());
            $updateUser->setSessionId(NULL);
            $this->userrepository->merge($updateUser);
            $this->userrepository->flush();
            unset($this->roles);
            unset($this->sessionId);
            unset($this->salespersoninplay);
            unset($this->user);
            $this->cookieService->deleteCookie("salesperson", $this->response);
            $this->cookieService->deleteCookie("requestedRoute", $this->response);
            $this->sessionManager->getStorage()->clear();
            while ($this->sessionManager->sessionExists()) {
                $this->sessionManager->destroy();
            }
        } catch (\Exception $exc) {
            $this->log(this::class, __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    public function doLookup() {
        try {
            $this->user = $this->userrepository->findBySessionId($this->sessionId);
            if (!empty($this->user)) {
                $roleXrefs = $this->userrolexrefrepository->findBy(array('username' => $this->user->getUsername()));
                $this->roles = [];
                foreach ($roleXrefs as $role) {
                    $this->roles[] = $role;
                }
                $salespersoninplayusernamecookie = $this->cookieService->getCookie('salesperson', $this->request);
                if (!empty($salespersoninplayusernamecookie)) {
                    $this->salespersoninplay = $this->userrepository->findUser($salespersoninplayusernamecookie);
                }
            } else {
                $this->log(this::class, __LINE__, "No Session data found.");
            }
        } catch (\Exception $exc) {
            $this->log(this::class, __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    public function addRequestedRoute($requestedRoute, $seconds) {
        if (empty($seconds)) {
            $seconds = $this->cookieService->getCookieLifetime();;
        }
        //adds a 1 hour cookie.
        $this->cookieService->setCookie('requestedRoute', $requestedRoute, $seconds, $this->response);
    }

    public function getRequestedRoute() {
        $cookie = $this->cookieService->getCookie('requestedRoute', $this->request);
        return !empty($cookie) ? $cookie : NULL;
    }

    public function addSalespersonInPlay($salespersoninplayusername) {
        $seconds = $this->cookieService->getCookieLifetime();;

        //adds a 1 hour cookie.
        $this->log(this::class, __LINE__, 'Added Salespersoninplay: ' . $salespersoninplayusername);
        $this->cookieService->setCookie('salesperson', $salespersoninplayusername, $seconds, $this->response);
    }

    public function admin() {
        $role = $this->getRole();
        $roleArr = array($role => $role);
        //$roleStr will be a boolean if there is no roles present in session.
        $this->log(this::class, __LINE__, strval($roleArr[$role]));
        return !empty($roleArr) ? array_key_exists("admin", $roleArr) : false;
    }

    public function getRole() {
        if (!empty($this->roles)) {
            return $this->roles[0]->getRole();
        }
        return FALSE;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getViewRoles() {
        $roles = [];
        $idx = 0;
        foreach ($this->roles as $role) {
            $roles[$idx++] = $role->getRole();
        }
        return $roles;
    }

    public function getSalespersonInPlay() {
        return $this->salespersoninplay;
    }

    public function getUser() {
        return $this->user;
    }

    public function getUserOrSalespersonInPlay() {
        $facade = empty($this->salespersoninplay);
        return $facade ? $this->getUser() :
                $this->getSalespersonInPlay();
    }

    //Private
    private function setUserRepository($userrepository) {
        $this->userrepository = $userrepository;
    }

    private function setUserRoleXrefRepository($userrolexrefrepository) {
        $this->userrolexrefrepository = $userrolexrefrepository;
    }

    private function setRequest($request) {
        $this->request = $request;
    }

    private function setResponse($response) {
        $this->response = $response;
    }

    private function setSessionManager($sessionManager) {
        $this->sessionManager = $sessionManager;
    }

    private function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

}
