<?php

namespace Sales\Service;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DateTime;
use ReflectionClass;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\View\Model\JsonModel;

/**
 * Description of SalesFormService
 *
 * @author jasonpalmer
 */
class SalesFormService {

    public function assembleRowPlusItemsPageAndArray(
            AuthenticationService $authService, 
            CustomerRepositoryImpl $customerrepository, 
            UserRepositoryImpl $userrepository, 
            RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, 
            Form $form, 
            array $jsonModelArr, 
            $customerid
    ) {
        if ($form->isValid()) {
            $success = true;
            $record = $this->getRowPlusItemsPageRecord($userrepository, $customerrepository, $authService, $customerid);
            $this->assembleReflectMethod(['sku', 'sku', 'sku', 'sku', 'sku', 'sku'], $jsonModelArr, $form, $record);
            $this->assembleReflectMethod(['productname', 'product', 'product', 'productname', 'productname', 'productname'], $jsonModelArr, $form, $record);
            $this->assembleReflectMethod(['shortescription', 'description', 'description', 'shortescription', 'shortescription', 'description'], $jsonModelArr, $form, $record);
            $this->assembleReflectMethod(['comment', 'comment', 'comment', 'comment', 'comment', 'comment'], $jsonModelArr, $form, $record);
            $this->assembleReflectMethod(['overrideprice', 'overrideprice', 'overrideprice', 'overrideprice', 'overrideprice', 'overrideprice'], $jsonModelArr, $form, $record);
            $this->assembleReflectMethod(['uom', 'uom', 'uom', 'uom', 'uom', 'uom'], $jsonModelArr, $form, $record);
            $this->setupArrayDefaults($jsonModelArr);
            Logger::info("SalesFormService", __LINE__, 'Sanitizing...');
            $rowplusitemspagerepository->persistAndFlush($record);
            $jsonModelArr["id"] = "A" . $record->getId();
            $jsonModelArr["success"] = $success;
        } else {
            foreach ($form->getMessages() as $message) {
                Logger::info("SalesFormService", __LINE__, 'Message: ' . print_r($message, true));
            }
        }
        return new JsonModel($jsonModelArr);
    }

    private function getRowPlusItemsPageRecord(
            UserRepositoryImpl $userrepository, 
            CustomerRepositoryImpl $customerrepository, 
            AuthenticationService $authService, 
            $customerid) {
        $record = new RowPlusItemsPage();
        $record->setStatus(true);
        $created = new DateTime("now");
        $record->setCreated($created);
        $record->setActive(true);
        $customer = $customerrepository->findCustomer($customerid);
        $record->setCustomer($customer);
        $salesperson = $userrepository->findUser($authService->getStorage()->getUserOrSalespersonInPlay()->getUsername());
        $record->setSalesperson($salesperson);
        return $record;
    }
    
    private function assembleReflectMethod(array $args, array &$data, Form $form, RowPlusItemsPage &$rowplusitemspage) {
        $data[$args[0]] = empty($form->getData()[$args[1]]) ? '' : $form->getData()[$args[2]];
        if (array_key_exists($args[3], $data)) {
            $reflectionClass = new ReflectionClass('DataAccess\FFM\Entity\RowPlusItemsPage');
            $reflectionProperty = $reflectionClass->getProperty($args[5]);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($rowplusitemspage, $data[$args[4]]);
        }
    }
    
    private function setupArrayDefaults(&$jsonModelArr) {
        $jsonModelArr["qty"] = '';
            $jsonModelArr["option"] = '';
            $jsonModelArr["wholesale"] = '';
            $jsonModelArr["retail"] = '';
            $jsonModelArr["status"] = "1";
            $jsonModelArr["saturdayenabled"] = "0";
    }

}
