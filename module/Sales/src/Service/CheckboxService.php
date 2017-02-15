<?php

namespace Sales\Service;

use Application\Service\LoggingService;
use Application\Utility\Logger;
use DataAccess\FFM\Entity\ItemTableCheckbox;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\ItemTableCheckboxRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use Exception;

/**
 * Description of CheckboxService
 *
 * @author jasonpalmer
 */
class CheckboxService  {

    protected $logger;
    protected $checkboxrepository;
    protected $userrepository;
    protected $customerrepository;
    protected $productrepository;
    protected $rowplusitemspagerepository;

    public function __construct(LoggingService $logger, ItemTableCheckboxRepositoryImpl $checkboxrepository, UserRepositoryImpl $userrepository, CustomerRepositoryImpl $customerrepository, ProductRepositoryImpl $productrepository, RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository) {
        $this->logger = $logger;
        $this->checkboxrepository = $checkboxrepository;
        $this->userrepository = $userrepository;
        $this->customerrepository = $customerrepository;
        $this->productrepository = $productrepository;
        $this->rowplusitemspagerepository = $rowplusitemspagerepository;
    }

    public function addRemovedID($id, $customerid, $salespersonusername) {
        $record;
        if (strpos($id, 'P') !== false){
            $record = $this->findCheckbox(null, substr($id, 1), $customerid, $salespersonusername);
        }else{
            $record = $this->findCheckbox(substr($id, 1), null, $customerid, $salespersonusername);
        }
        if (!empty($record)) {
            $record->setChecked(true);
        } else {
            if (empty(is_array($id))) {
                $id = array($id);
            }
            foreach ($id as $i) {
                //here the $i will either be prefixed with 'A' or 'P' for AddedProduct OR Product respectively.
                Logger::info("CheckboxService", __LINE__, "Creating and persisting Checkbox record. ID: " . $i . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
                $record = new ItemTableCheckbox();
                $user = $this->findUser($salespersonusername);
                $customer = $this->findCustomer($customerid);
                if (strpos($i, 'P') !== false) {
                    //here we are dealing with a Product
                    $product = $this->findProduct(substr($i, 1));//trim P
                    $record->setProduct($product);
                } else if (strpos($i, 'A') !== false){
                    //here we are dealing with a RowPlusitemsPage.
                    $rowplusitemspage = $this->findRowPlusItemsPage(substr($i, 1));
                    $record->setRowPlusItemsPage($rowplusitemspage);
                }else{
                    throw new Exception();
                }

                $record->setSalesperson($user);
                $record->setChecked(true);
                $record->setCustomer($customer);
                $this->checkboxrepository->persist($record);
            }
        }
        $this->checkboxrepository->flush();
    }

    public function getRemovedIDS($customerid, $salespersonusername) {
        //return 1d array of skus
        $arrayOfCheckboxEntities = $this->checkboxrepository->
                getAllIDsByCustomerIdAndSalesperson($salespersonusername, $customerid);
        //create non-associate (1d) array of SKUs. This will eliminate the need
        $onedim = array();
        foreach ($arrayOfCheckboxEntities as $entity) {
            if ($entity->getChecked()) {
                $productorrowplusitemspage = !empty($entity->getProduct()) ? $entity->getProduct() : $entity->getRowPlusItemsPage();
                $onedim [] = $productorrowplusitemspage;
            }
        }
        return $onedim;
    }

    public function removeRemovedID($id, $customerid, $salespersonusername) {
        //first find the record
        $record;
        if (strpos($id, 'P') !== false){
            $record = $this->findCheckbox(null, substr($id, 1), $customerid, $salespersonusername);
        }else if (strpos($id, 'A') !== false){
            $record = $this->findCheckbox(substr($id, 1), null, $customerid, $salespersonusername);
        }else{
            throw new Exception();
        }
        if (!empty($record)) {
            $record->setChecked(false);
        } else {
            Logger::info("CheckboxService", __LINE__, "Checkbox record not found to remove. ID: " . $id . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
        }
        $this->checkboxrepository->removeCheckbox($id, $customerid, $salespersonusername);
    }

    protected function findCheckbox($rowplusitemspage, $id, $customerid, $salespersonusername) {
        return $this->checkboxrepository->findCheckbox($rowplusitemspage, $id, $customerid, $salespersonusername);
    }

    protected function findUser($username) {
        return $this->userrepository->findUser($username);
    }

    protected function findCustomer($customerid) {
        return $this->customerrepository->findCustomer($customerid);
    }
    
    protected function findRowPlusItemsPage($id) {
        return $this->rowplusitemspagerepository->findRowPlusItemsPage($id);
    }

    protected function findProduct($productid) {
        return $this->productrepository->findProduct($productid);
    }

}
