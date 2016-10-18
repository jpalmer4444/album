<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Model;

use Zend\Form\Form;

class User extends Form {

    public function __construct() {
        
        parent::__construct();
        
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Username',
                'required' => TRUE,
                'autofocus' => TRUE
            ),
            'options' => array(
                'label' => 'Username'
            ),
            'type'  => 'Text',
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Password',
                'required' => TRUE
            ),
            'options' => array(
                'label' => 'Password'
            ),
            'type'  => 'Password',
        ));
        
        $this->add(array(
            'name' => 'rememberme',
            'options' => array(
                'value' => 'Remember Me?',
            ),
            'type'  => 'Checkbox',
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Login',
                'class' => 'btn btn-lg btn-primary btn-block'
            ),
        ));
        
    }

    public $username;
    
    public $password;

    public $rememberme;

    public $submit;
    
    public $csrf;

}
