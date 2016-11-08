<?php

namespace DataAccess\FFM\Entity;

use Zend\Form\Form;

/**
 *
 * @author jasonpalmer
 */
interface PostFormBinderInterface{
    
    public function bind(Form $form, $postData);
    
}
