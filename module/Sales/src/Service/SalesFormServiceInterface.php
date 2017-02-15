<?php

namespace Sales\Service;

/*
 * FormServiceInterface
 */

use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;

/**
 *
 * @author jasonpalmer
 */
interface SalesFormServiceInterface {
    
    public function assembleRowPlusItemsPageAndArray(AuthenticationService $authService, CustomerRepositoryImpl $customerrepository, UserRepositoryImpl $userrepository, RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, Form $form, array $jsonModelArr, $customerid);
    
}
