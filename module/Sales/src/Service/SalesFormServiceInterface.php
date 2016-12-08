<?php

namespace Sales\Service;

/*
 * FormServiceInterface
 */

use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use Login\Model\MyAuthStorage;
use Zend\Form\Form;

/**
 *
 * @author jasonpalmer
 */
interface SalesFormServiceInterface {
    
    public function postRowPlusItemsPage(MyAuthStorage $myauthstorage, CustomerRepositoryImpl $customerrepository, UserRepositoryImpl $userrepository, RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, Form $form, array $jsonModelArr, $customerid);
    
}
