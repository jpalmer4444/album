<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\Service\CheckboxService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of CheckboxServiceFactory
 *
 * @author jasonpalmer
 */
class CheckboxServiceFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $loggingService = $container->get('LoggingService');
        $entityManager = $container->get('EntityService');
        $checkboxrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\ItemTableCheckbox');
        $userrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $customerrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\Customer');
        $productrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\Product');
        $rowplusitemspagerepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\RowPlusItemsPage');
        return new CheckboxService($loggingService, $checkboxrepository, $userrepository, $customerrepository, $productrepository, $rowplusitemspagerepository);
    }

}
