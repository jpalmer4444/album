<?php

namespace Sales;

use Sales\Controller\ItemsController;
use Sales\Controller\SalesController;
use Sales\Controller\UsersController;
use Sales\DTO\RowPlusItemsPageForm;
use Sales\Filter\RowPlusItemsPageInputFilter;
use Sales\Service\CheckboxService;
use Zend\Hydrator\ObjectProperty;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface{

    const VERSION = '3.0.2dev';

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                UsersController::class => function($container) {
                    return new UsersController(
                            $container
                    );
                },
                ItemsController::class => function($container) {
                    $loggingService = $container->get('LoggingService');
                    $myauthstorage = $container->get('Login\Model\MyAuthStorage');
                    $pricingconfig = $container->get('config')['pricing_config'];
                    $formManager = $container->get('FormElementManager');
                    $userrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
                    $rowplusitemspagerepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\RowPlusItemsPage');
                    $customerrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Customer');
                    $productrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Product');
                    $userproductrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserProduct');
                    return new ItemsController(
                            $loggingService,
                            $myauthstorage,
                            $formManager,
                            $userrepository,
                            $rowplusitemspagerepository,
                            $customerrepository,
                            $productrepository,
                            $userproductrepository,
                            $pricingconfig
                    );
                },
                SalesController::class => function($container) {
                    return new SalesController(
                            $container,
                            $container->get('FFMEntityManager')
                    );
                },
            ],
        ];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                'CheckboxService' => function($sm) {
                    $loggingService = $sm->get('LoggingService');
                    $entityManager = $sm->get('FFMEntityManager');
                    return new CheckboxService($loggingService, $entityManager);
                },
            ]
        ];
    }

    public function getFormElementConfig() {
        return array(
            'factories' => array(
                'RowPlusItemsPageForm' => function($sm) {
                    $form = new RowPlusItemsPageForm();
                    $form->setInputFilter(new RowPlusItemsPageInputFilter());
                    $form->setHydrator(new ObjectProperty());
                    return $form;
                },
            ),
        );
    }

}
