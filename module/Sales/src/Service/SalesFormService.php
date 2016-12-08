<?php

namespace Sales\Service;

use Application\Service\LoggingServiceInterface;
use DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\RowPlusItemsPageRepositoryImpl;
use DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DateTime;
use Login\Model\MyAuthStorage;
use Zend\Form\Form;
use Zend\View\Model\JsonModel;

/**
 * Description of SalesFormService
 *
 * @author jasonpalmer
 */
class SalesFormService implements SalesFormServiceInterface {

    protected $logger;

    public function __construct(LoggingServiceInterface $logger) {
        $this->logger = $logger;
    }

    public function postRowPlusItemsPage(MyAuthStorage $myauthstorage, CustomerRepositoryImpl $customerrepository, UserRepositoryImpl $userrepository, RowPlusItemsPageRepositoryImpl $rowplusitemspagerepository, Form $form, array $jsonModelArr, $customerid) {
        if ($form->isValid()) {
            $success = true;
            $record = $this->getRowPlusItemsPageRecord();
            
            $jsonModelArr["sku"] = empty($form->getData()['sku']) ? '' : $form->getData()['sku'];
            if (array_key_exists("sku", $jsonModelArr)) {
                $record->setSku($jsonModelArr["sku"]);
            }
            $jsonModelArr["productname"] = empty($form->getData()['product']) ? '' : $form->getData()['product'];
            if (array_key_exists("productname", $jsonModelArr)) {
                $record->setProductname($jsonModelArr["productname"]);
            }
            $jsonModelArr["shortescription"] = empty($form->getData()['description']) ? '' : $form->getData()['description'];
            if (array_key_exists("shortescription", $jsonModelArr)) {
                $record->setDescription($jsonModelArr["shortescription"]);
            }
            $jsonModelArr["comment"] = empty($form->getData()['comment']) ? '' : $form->getData()['comment'];
            if (array_key_exists("comment", $jsonModelArr)) {
                $record->setComment($jsonModelArr["comment"]);
            }
            $jsonModelArr["qty"] = '';
            $jsonModelArr["option"] = '';
            $jsonModelArr["wholesale"] = '';
            $jsonModelArr["retail"] = '';
            $this->logger->info('SalesFormService:51: Sanitizing...');
            $jsonModelArr["overrideprice"] = empty($form->getData()['overrideprice']) ? '' : $form->getData()['overrideprice'];
            if (array_key_exists("overrideprice", $jsonModelArr)) {
                $record->setOverrideprice($jsonModelArr["overrideprice"]);
            }
            $jsonModelArr["uom"] = empty($form->getData()['uom']) ? '' : $form->getData()['uom'];
            if (array_key_exists("uom", $jsonModelArr)) {
                $record->setUom($jsonModelArr["uom"]);
            }
            $jsonModelArr["status"] = "1";
            $record->setStatus(true);
            $jsonModelArr["saturdayenabled"] = "0";
            $created = new DateTime("now");
            $record->setCreated($created);
            $record->setActive(true);
            $customer = $customerrepository->findCustomer($customerid);
            $record->setCustomer($customer);
            $salesperson = $userrepository->findUser(empty($myauthstorage->getSalespersonInPlay()) ? $myauthstorage->getUser()->getUsername() : $myauthstorage->getSalespersonInPlay()->getUsername());
            $record->setSalesperson($salesperson);
            //var_dump($record);
            $rowplusitemspagerepository->persistAndFlush($record);
            $jsonModelArr["id"] = "A" . $record->getId();
            $jsonModelArr["success"] = $success;
        } else {
            foreach ($form->getMessages() as $message) {
                $this->logger->info('Message: ' . print_r($message, true));
            }
        }
        return new JsonModel($jsonModelArr);
    }

    private function getRowPlusItemsPageRecord() {
        $record = new RowPlusItemsPage();
        $record->setLogger($this->logger);
        return $record;
    }

}
