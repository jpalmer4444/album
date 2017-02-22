<?php

namespace Sales\Factory;

use Application\Utility\Strings;
use Interop\Container\ContainerInterface;
use Sales\Controller\ItemsController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ItemsControllerFactory
 *
 * @author jasonpalmer
 */
class ItemsControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $loggingService = $container->get('LoggingService');
                    $sessionService = $container->get('SessionService');
                    $pricingconfig = $container->get('config')['pricing_config'];
                    $formManager = $container->get('FormElementManager');
                    $salesFromService = $container->get('SalesFormService');
                    $userrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
                    $rowplusitemspagerepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\RowPlusItemsPage');
                    $customerrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Customer');
                    $productrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Product');
                    $userproductrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserProduct');
                    $pricingoverridereportrepository = $container->get('EntityService')->getEntityManager()->getRepository('DataAccess\FFM\Entity\PricingOverrideReport');
                    $itemsController = new ItemsController(
                            $loggingService,
                            $sessionService,
                            $formManager,
                            $userrepository,
                            $rowplusitemspagerepository,
                            $customerrepository,
                            $productrepository,
                            $userproductrepository,
                            $pricingoverridereportrepository,
                            $salesFromService,
                            $pricingconfig
                    );
                    
                    //set BaseController properties.
                    $itemsController->setDbAdapter($container->get(Strings::ZEND_DB_ADAPTER));
                    $itemsController->setAuthService($container->get(Strings::AUTH_SERVICE));
                    $itemsController->setConfig($container->get('Config'));
                    
                    //return the controller
                    return $itemsController;
    }
    
}
