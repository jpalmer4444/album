<?php

/*
 * Is Session Storage and is serialized to Redis and keyed by
 * PHPSessionID:MyAuthStorage...
 * Any properties added to this class that need to be persistent must be added to the toArray()
 * and getInstance() functions. This is necessary in order for Redis to be able to properly store
 * and restore (serialize and unserialize) the session state from Redis.
 */

namespace Login\Model;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\User;
use Zend\Authentication\Storage\Session;

class MyAuthStorage extends Session {

    public function setRememberMe($rememberMe = 0, $time = 1209600) {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe() {
        $this->session->getManager()->forgetMe();
        $this->session->getManager()->getStorage()->clear();
    }

    public function addRoles($roles) {
        $this->session->getManager()->getStorage()['roles'] = $roles;
    }

    public function getRole() {
        if ($this->getRoles()) {
            return $this->getRoles()[0]->getRole();
        }
        return FALSE;
    }

    public function admin() {
        $role = $this->getRole();
        $roleArr = array($role => $role);
        //$roleStr will be a boolean if there is no roles present in session.
        Logger::info("MyAuthStorage", __LINE__, strval($roleArr[$role]));
        return !empty($roleArr) ? array_key_exists("admin", $roleArr) : $roleArr;
    }

    public function getRoles() {
        return $this->session->getManager()->getStorage()['roles'];
    }

    public function addUser(User $user) {
        $this->session->getManager()->getStorage()['user'] = $user;
        $this->session->getManager()->getStorage()['username'] = $user->getUsername();
    }

    public function getUser() {
        return $this->session->getManager()->getStorage()['user'];
    }

    public function addRequestedRoute($route) {
        $this->session->getManager()->getStorage()['requestedRoute'] = $route;
    }

    public function getRequestedRoute() {
        return $this->session->getManager()->getStorage()['requestedRoute'];
    }

    public function getUserOrSalespersonInPlay() {
        $facade = empty($this->getSalespersonInPlay());
        return $facade ? $this->getUser() :
                $this->getSalespersonInPlay();
    }

    /**
     * 
     * @param User $userModel
     */
    public function addSalespersonInPlay(User $userModel) {
        $this->session->getManager()->getStorage()['salespersoninplay'] = $userModel;
    }

    public function getSalespersonInPlay() {
        return $this->session->getManager()->getStorage()['salespersoninplay'];
    }

    public function toArray() {
        $array = array();
        if(!empty($this->getRoles())){
            $array['roles'] = $this->getRoles();
        }
        if(!empty($this->getUser())){
            $array['user'] = $this->getUser();
        }
        if(!empty($this->getRequestedRoute())){
            $array['requestedRoute'] = $this->getRequestedRoute();
        }
        if(!empty($this->getSalespersonInPlay())){
            $array['salespersoninplay'] = $this->getSalespersonInPlay();
        }
        return $array;
    }
    
    public static function getInstance($array) {
        $myauthstorage = new MyAuthStorage();
        if(array_key_exists('roles', $array)){
            $myauthstorage->addRoles($array['roles']);
        }
        if(array_key_exists('user', $array)){
            $myauthstorage->addUser($array['user']);
        }
        if(array_key_exists('requestedRoute', $array)){
            $myauthstorage->addRequestedRoute($array['requestedRoute']);
        }
        if(array_key_exists('salespersoninplay', $array)){
            $myauthstorage->addSalespersonInPlay($array['salespersoninplay']);
        }
        return $myauthstorage;
    }

    /*
     * The Following 3 methods should no longer be used.
     * addRemovedSKU($sku, $customerid)
     * removeRemovedSKU($sku, $customerid)
     * getRemovedSKUS($customerid)
     * As of yet - I have left the logic in tact in the case that we later 
     * want to move to session persistence again.
     */

    //holds references to deleted or removed rows from items page. logout to reset.
    public function addRemovedSKU($sku, $customerid) {
        $localSKUS = is_array($sku) ? $sku : array(sku);
        $custstorage = $this->session->getManager()->getStorage()['_' . $customerid];
        if (empty($custstorage)) {
            $this->session->getManager()->getStorage()['_' . $customerid] = array();
            $custstorage = $this->session->getManager()->getStorage()['_' . $customerid];
        }
        $removed = [];
        if (array_key_exists("removedSKUS", $custstorage)) {
            $removed = $custstorage['removedSKUS'];
        }
        if (empty($removed)) {
            $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'] = $localSKUS;
        } else {
            foreach ($localSKUS as $_sku) {
                $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'][] = $_sku;
            }
        }
    }

    public function removeRemovedSKU($sku, $customerid) {
        $removed = $this->getRemovedSKUS($customerid);
        $renewed = array();
        if (is_string($sku)) {
            $sku = array($sku);
        }
        foreach ($removed as $s) {
            foreach ($sku as $_sku) {
                if (strcmp($_sku, $s) != 0) {
                    $renewed [] = $s;
                }
            }
        }
        $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'] = $renewed;
    }

    public function getRemovedSKUS($customerid) {
        if (!empty($this->session->getManager()->getStorage()['_' . $customerid])) {
            return $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'];
        }
        return array();
    }

}
