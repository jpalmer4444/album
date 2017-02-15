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

    public function __construct(UserRepositoryImpl $userrepository, UserRoleXrefRepositoryImpl $userrolexrefrepository, $sessionId) {
        $this->sessionId = $sessionId;
        $this->userrepository = $userrepository;
        $this->userrolexrefrepository = $userrolexrefrepository;
        $this->doLookup();
    }

    public function login() {
        $updateUser = $this->userrepository->findUser($this->user->getUsername());
        $updateUser->setSessionId($this->sessionId);
        $updateUser->setLastLogin(new DateTime());
        $this->userrepository->persistAndFlush($updateUser);
    }

    public function logout() {
        $updateUser = $this->userrepository->findUser($this->user->getUsername());
        $updateUser->setSessionId(NULL);
        $this->userrepository->persistAndFlush($updateUser);
    }

    public function doLookup() {
        $this->user = $this->userrepository->findBySessionId($this->sessionId);
        if (!empty($this->user)) {
            $roleXrefs = $this->userrolexrefrepository->findBy(array('username' => $this->user->getUsername()));
            $this->roles = [];
            foreach ($roleXrefs as $role) {
                $this->roles[] = $role;
            }
            $salespersoninplayusername = $_COOKIE('salespersoninplayusername');
            if (!empty($salespersoninplayusername)) {
                $this->salespersoninplay = $this->userrepository->findUser($salespersoninplayusername);
            }
        }else{
            Logger::info("SessionService", __LINE__, "No Session data found.");
        }
    }

    public function addRequestedRoute($requestedRoute, $seconds = 3600) {
        //adds a 1 hour cookie.
        setcookie('requestedRoute', $requestedRoute, time() + $seconds, '/');
    }

    public function getRequestedRoute() {
        return $_COOKIE('requestedRoute');
    }

    public function addSalespersonInPlay($salespersoninplayusername, $seconds = 86400) {
        //adds a 1 hour cookie.
        setcookie('salespersoninplayusername', $salespersoninplayusername, time() + $seconds, '/');
    }

    public function admin() {
        $role = $this->getRole();
        $roleArr = array($role => $role);
        //$roleStr will be a boolean if there is no roles present in session.
        Logger::info("SessionService", __LINE__, strval($roleArr[$role]));
        return !empty($roleArr) ? array_key_exists("admin", $roleArr) : $roleArr;
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
