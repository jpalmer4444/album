<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

/**
 *
 * @author jasonpalmer
 */
interface PredisServiceInterface {
    
    public function get($key);
    public function getMyAuthStorage();
    public function set($key, $value);
    public function serialize($clazz);
    public function unserialize($data);
    
}
