<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Login\Model;

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
        $this->session->getManager()->getStorage()['roles'] = NULL;
        $this->session->getManager()->getStorage()['user'] = NULL;
        $this->session->getManager()->getStorage()['requestedRoute'] = NULL;
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
    
    public function addUser($user) {
        $this->session->getManager()->getStorage()['user'] = $user;
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
    
}
