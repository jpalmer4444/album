<?php

namespace Sales\Factory;

use Sales\Controller\ItemsController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ItemsControllerFactory
 *
 * @author jasonpalmer
 */
class ItemsControllerFactory {
    
    public function __invoke(ServiceLocatorInterface $container) {
        $loggingService = $container->get('LoggingService');
                    $myauthstorage = $container->get('Login\Model\MyAuthStorage');
                    $pricingconfig = $container->get('config')['pricing_config'];
                    $formManager = $container->get('FormElementManager');
                    $salesFromService = $container->get('SalesFormService');
                    $userrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
                    $rowplusitemspagerepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\RowPlusItemsPage');
                    $customerrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Customer');
                    $productrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\Product');
                    $userproductrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\UserProduct');
                    $pricingoverridereportrepository = $container->get('FFMEntityManager')->getEntityManager()->getRepository('DataAccess\FFM\Entity\PricingOverrideReport');
                    return new ItemsController(
                            $loggingService,
                            $myauthstorage,
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
    }
    
}
