<?php

namespace Sales\Factory;

use Sales\Service\CheckboxService;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of CheckboxServiceFactory
 *
 * @author jasonpalmer
 */
class CheckboxServiceFactory {

    public function __invoke(ServiceManager $sm) {
        $loggingService = $sm->get('LoggingService');
        $entityManager = $sm->get('FFMEntityManager');
        $checkboxrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\ItemTableCheckbox');
        $userrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
        $customerrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\Customer');
        $productrepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\Product');
        $rowplusitemspagerepository = $entityManager->getEntityManager()->getRepository('DataAccess\FFM\Entity\RowPlusItemsPage');
        return new CheckboxService($loggingService, $checkboxrepository, $userrepository, $customerrepository, $productrepository, $rowplusitemspagerepository);
    }

}
