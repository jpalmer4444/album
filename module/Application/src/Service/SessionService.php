<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRoleXrefRepositoryImpl;
use DateTime;
use Exception;
use Zend\Http\Header\SetCookie;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Session\SessionManager;

/**
 * Description of SessionService
 *
 * @author jasonpalmer
 */
class SessionService {

    protected $sessionId;
    protected $userrepository;
    protected $userrolexrefrepository;
    protected $roles;
    protected $salespersoninplay;
    protected $user;
    protected $request;

    public function __construct(UserRepositoryImpl $userrepository, UserRoleXrefRepositoryImpl $userrolexrefrepository, SessionManager $sessionManager, Request $request, Response $response) {
        //$sessionManager->start();
        $this->sessionId = $sessionManager->getId();
        if(!$this->sessionId){
            $sessionManager->start();
            $this->sessionId = $sessionManager->getId();
        }
        Logger::info("SessionService", __LINE__, "(CONSTRUCTOR) Session Id: " . $this->sessionId);
        $this->userrepository = $userrepository;
        $this->userrolexrefrepository = $userrolexrefrepository;
        $this->request = $request;
        $this->response = $response;
        $this->doLookup();
    }

    public function login(SessionManager $sessionManager, $username, $session_id = NULL) {
        try {
            $this->sessionId = !empty($session_id) ? $session_id : $sessionManager->getId();
            Logger::info("SessionService", __LINE__, "(LOGIN) Session Id: " . $this->sessionId);
            Logger::info("SessionService", __LINE__, "(LOGIN) username: " . $username);
            $user = $this->userrepository->findUser($username);
            $user->setSessionId($this->sessionId);
            $user->setLastLogin(new DateTime());
            $this->userrepository->mergeAndFlush($user);
            $this->user = $user;
            Logger::info("SessionService", __LINE__, "(LOGIN) Saved Session ID: " . $user->getSessionId());
            if (!empty($this->user)) {
                $roleXrefs = $this->userrolexrefrepository->findBy(array('username' => $this->user->getUsername()));
                $this->roles = [];
                $idx = 0;
                Logger::info("SessionService", __LINE__, "(LOGIN) Adding Roles: " . print_r($roleXrefs, TRUE));
                foreach ($roleXrefs as $role) {
                    $this->roles[$idx++] = $role;
                }
                $salespersoninplayusernamecookie = $this->getCookie('salesperson');
                Logger::info("SessionService", __LINE__, "(LOGIN) Looked up salesperson: " . (!empty($salespersoninplayusernamecookie) ? $salespersoninplayusernamecookie : "NULL"));
                if (!empty($salespersoninplayusernamecookie)) {
                    $this->salespersoninplay = $this->userrepository->findUser($salespersoninplayusernamecookie);
                }
            } else {
                Logger::info("SessionService", __LINE__, "Login Failed!");
            }
        } catch (Exception $exc) {
            Logger::info("SessionService", __LINE__, "Exception: " . $exc->getTraceAsString());
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
            $this->deleteCookie("salesperson");
            $this->deleteCookie("requestedRoute");
        } catch (Exception $exc) {
            Logger::info("SessionService", __LINE__, "Exception: " . $exc->getTraceAsString());
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
                $salespersoninplayusernamecookie = $this->getCookie('salesperson');
                if (!empty($salespersoninplayusernamecookie)) {
                    $this->salespersoninplay = $this->userrepository->findUser($salespersoninplayusernamecookie);
                }
            } else {
                Logger::info("SessionService", __LINE__, "No Session data found.");
            }
        } catch (\Exception $exc) {
            Logger::info("SessionService", __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    private function setCookie($name, $value, $timeplus = 3600) {
        try {
            $this->createCookie($name, $value, $timeplus);
        } catch (Exception $exc) {
            Logger::info("SessionService", __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    private function createCookie($name, $value, $timeplus = 3600) {
        // create a cookie
        $cookie = new SetCookie(
                $name, $value, time() + $timeplus, // 1 year lifetime
                '/'
        );

        $this->response->getHeaders()->addHeader($cookie);
    }

    private function deleteCookie($name) {
        $cookie = new SetCookie(
                $name, '', strtotime('-1 Year', time()), // -1 year lifetime (negative from now)
                '/'
        );
        $this->response->getHeaders()->addHeader($cookie);
    }

    private function getCookie($name) {
        try {
            $cookie = $this->request->getCookie();
            if ($cookie->offsetExists($name)) {
                return $cookie->offsetGet($name);
            }
        } catch (Exception $exc) {
            Logger::info("SessionService", __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

    public function addRequestedRoute($requestedRoute, $seconds = 3600) {
        //adds a 1 hour cookie.
        $this->setCookie('requestedRoute', $requestedRoute, $seconds);
    }

    public function getRequestedRoute() {
        $cookie = $this->getCookie('requestedRoute');
        return !empty($cookie) ? $cookie : NULL;
    }

    public function addSalespersonInPlay($salespersoninplayusername, $seconds = 3600) {
        //adds a 1 hour cookie.
        Logger::info("UsersController", __LINE__, 'Added Salespersoninplay: ' . $salespersoninplayusername);
        $this->setCookie('salesperson', $salespersoninplayusername, $seconds);
    }

    public function admin() {
        $role = $this->getRole();
        $roleArr = array($role => $role);
        //$roleStr will be a boolean if there is no roles present in session.
        Logger::info("SessionService", __LINE__, strval($roleArr[$role]));
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

}
