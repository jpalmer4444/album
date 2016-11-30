<?php

namespace DataAccess\FFM\Entity\Factory\Form;

use DataAccess\FFM\Entity\Form\UserForm;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UserFormFactory
 *
 * @author jasonpalmer
 */
class UserFormFactory {
    
    public function __invoke(ServiceLocatorInterface $services) {
        $objectManager = $services->get('FFMEntityManager')->getEntityManager();
        $userForm    = new UserForm($objectManager);
        return $userForm;
    }

}
