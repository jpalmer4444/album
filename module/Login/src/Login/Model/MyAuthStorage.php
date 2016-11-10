<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Login\Model;

use DataAccess\FFM\Entity\User;
use Zend\Authentication\Storage\Session;
 
class MyAuthStorage extends Session
{
    
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
         if ($rememberMe == 1) {
             $this->session->getManager()->rememberMe($time);
         }
    }
     
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
        $this->session->getManager()->getStorage()->clear();
        
    } 
    
    public function addRoles($roles) {
        $this->session->getManager()->getStorage()['roles'] = $roles;
    }
    
    public function getRole(){
        if($this->getRoles()){
            return $this->getRoles()[0]->getRole();
        }
        return FALSE;
    }
    
    public function admin(){
        $roleStr = $this->getRole();
        //$roleStr will be a boolean if there is no roles present in session.
        return $roleStr ? strcmp($roleStr, "admin") == 0 : $roleStr; 
    }
    
    public function getRoles(){
        return $this->session->getManager()->getStorage()['roles'];
    }
    
    public function addUser(User $user) {
        $this->session->getManager()->getStorage()['user'] = $user;
        $this->session->getManager()->getStorage()['username'] = $user->getUsername();
    }
    
    public function getUser(){
        return $this->session->getManager()->getStorage()['user'];
    }
    
    public function addRequestedRoute($route) {
        $this->session->getManager()->getStorage()['requestedRoute'] = $route;
    }
    
    public function getRequestedRoute(){
        return $this->session->getManager()->getStorage()['requestedRoute'];
    }
    
    /**
     * 
     * @param User $userModel
     */
    public function addSalespersonInPlay(User $userModel) {
        $this->session->getManager()->getStorage()['salespersoninplay'] = $userModel;
    }
    
    public function getSalespersonInPlay(){
        return $this->session->getManager()->getStorage()['salespersoninplay'];
    }
    
    //holds references to deleted or removed rows from items page. logout to reset.
    public function addRemovedSKU($sku, $customerid) {
        $custstorage = $this->session->getManager()->getStorage()['_' . $customerid];
        if(empty($custstorage)){
            $this->session->getManager()->getStorage()['_' . $customerid] = array();
            $custstorage = $this->session->getManager()->getStorage()['_' . $customerid];
        }
        $removed = [];
        if(array_key_exists("removedSKUS", $custstorage)){
            $removed = $custstorage['removedSKUS'];
        }
        if(empty($removed)){
            $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'] = array($sku);
        }else{
            $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'][] = $sku;
        }
    }
    
    public function getRemovedSKUS($customerid){
        if(!empty($this->session->getManager()->getStorage()['_' . $customerid])){
            return $this->session->getManager()->getStorage()['_' . $customerid]['removedSKUS'];
        }
    }
    
}
