<?php

namespace Sales\Service;

use Application\Service\FFMEntityManagerServiceInterface;
use Application\Service\LoggingServiceInterface;
use DataAccess\FFM\Entity\ItemTableCheckbox;
use Sales\Service\CheckboxServiceInterface;

/**
 * Description of CheckboxService
 *
 * @author jasonpalmer
 */
class CheckboxService implements CheckboxServiceInterface {

    protected $logger;
    protected $checkboxrepository;
    protected $userrepository;

    public function __construct(LoggingServiceInterface $logger, FFMEntityManagerServiceInterface $ffmEntityManagerService) {
        $this->logger = $logger;
        $this->checkboxrepository = $ffmEntityManagerService->getEntityManager()->getRepository('DataAccess\FFM\Entity\ItemTableCheckbox');
        $this->userrepository = $ffmEntityManagerService->getEntityManager()->getRepository('DataAccess\FFM\Entity\User');
    }

    public function addRemovedID($id, $customerid, $salespersonusername) {
        $record = $this->findCheckbox($id, $customerid, $salespersonusername);
        if (!empty($record)) {
            $record->setChecked(true);
        } else {
            if(empty(is_array($id))){
                $id = array($id);
            }
            foreach ($id as $i) {
                $this->logger->info("Creating and persisting Checkbox record. ID: " . $i . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
                $record = new ItemTableCheckbox();
                $user = $this->findUser($salespersonusername);
                $record->setSalesperson($user->getUsername());
                $record->setChecked(true);
                $record->setCustomerid($customerid);
                $record->setProduct($i);
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
            if($entity->getChecked()){
                $onedim [] = $entity->getProduct();   
            }
        }
        return $onedim;
    }

    public function removeRemovedID($id, $customerid, $salespersonusername) {
        //first find the record
        $record = $this->findCheckbox($id, $customerid, $salespersonusername);
        if (!empty($record)) {
            $record->setChecked(false);
        } else {
            $this->logger->info("Checkbox record not found to remove. ID: " . $id . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
        }
        $this->checkboxrepository->removeCheckbox($id, $customerid, $salespersonusername);
    }

    protected function findCheckbox($id, $customerid, $salespersonusername) {
        return $this->checkboxrepository->findCheckbox($id, $customerid, $salespersonusername);
    }

    protected function findUser($username) {
        return $this->userrepository->findUser($username);
    }

}
