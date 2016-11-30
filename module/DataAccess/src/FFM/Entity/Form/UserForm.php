<?php

namespace DataAccess\FFM\Entity\Form;

use DataAccess\FFM\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\ModuleManager\Feature\InputFilterProviderInterface;

/**
 * Form for Entity User
 *
 * @author jasonpalmer
 */
class UserForm extends Form implements InputFilterProviderInterface {

    public function __construct(ObjectManager $objectManager) {

        parent::__construct('user');

        $this->setHydrator(new DoctrineHydrator($objectManager))
                ->setObject(new User());

        $this->add([
            'type' => 'Zend\Form\Element\Text',
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'passwordCheck',
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                        'label' => 'Verify Password',
                    ),
                ),
            ),
        ));

        $this->add([
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Tel',
            'name' => 'phone1',
            'options' => [
                'label' => 'Phone',
            ],
        ]);
    }

    public function getInputFilterConfig() {
        return [
            'username' => [
                'required' => true,
            ],
            'password' => [
                'required' => true,
            ],
            'passwordCheck' => [
                'required' => true,
            ],
            'email' => [
                'required' => true,
            ],
            'phone1' => [
                'required' => true,
            ],
        ];
    }

}
