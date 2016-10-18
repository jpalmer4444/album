<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace REST\Service;

/**
 *
 * @author jasonpalmer
 */
interface RESTServiceInterface {
    
    /**
      * Should return user's list of options that we can iterate over. Single entries of the array are supposed to be
      * implementing \REST\Model\UserListInterface
      *
      * @return array|UserListInterface[]
      */
     public function findUserList($userId);
    
}
