<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Model;

use Exception;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User extends Form implements InputFilterAwareInterface {

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
    
    public $inputFilter;
    
         public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new Exception("Not used");
     }

     public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'username',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 50,
                         ),
                     ),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'password',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 50,
                         ),
                     ),
                 ),
             ));

             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }

}
