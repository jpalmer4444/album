<?php

namespace DataAccess\FFM\Entity\Factory\Form;

use DataAccess\FFM\Entity\Form\UserForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UserFormFactory
 *
 * @author jasonpalmer
 */
class UserFormFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $services, $requestedName, array $options = NULL) {
        $objectManager = $services->get('EntityService')->getEntityManager();
        $userForm    = new UserForm($objectManager);
        return $userForm;
    }

}
