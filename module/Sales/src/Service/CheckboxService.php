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
    protected $entityManager;
    protected $checkboxrepository;
    protected $userrepository;

    public function __construct(LoggingServiceInterface $logger, FFMEntityManagerServiceInterface $ffmEntityManagerService) {
        $this->logger = $logger;
        $this->entityManager = $ffmEntityManagerService->getEntityManager();
        $this->checkboxrepository = $this->entityManager->getRepository('DataAccess\FFM\Entity\ItemTableCheckbox');
        $this->userrepository = $this->entityManager->getRepository('DataAccess\FFM\Entity\User');
    }

    public function addRemovedSKU($sku, $customerid, $salespersonusername) {
        $record = $this->findCheckbox($sku, $customerid, $salespersonusername);
        if (!empty($record)) {
            $record->setChecked(true);
        } else {
            if(empty(is_array($sku))){
                $sku = array($sku);
            }
            foreach ($sku as $s) {
                $this->logger->info("Creating and persisting Checkbox record. SKU: " . $s . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
                $record = new ItemTableCheckbox();
                $user = $this->findUser($salespersonusername);
                $record->setSalesperson($user->getUsername());
                $record->setChecked(true);
                $record->setCustomerid($customerid);
                $record->setSku($s);
                $this->entityManager->persist($record);
            }
            $this->entityManager->flush();
        }
    }

    public function getRemovedSKUS($customerid, $salespersonusername) {
        //return 1d array of skus
        $arrayOfCheckboxEntities = $this->checkboxrepository->
                getAllSkusByCustomerIdAndSalesperson($salespersonusername, $customerid);
        //create non-associate (1d) array of SKUs. This will eliminate the need
        $onedim = array();
        foreach ($arrayOfCheckboxEntities as $entity) {
            if($entity->getChecked()){
                $onedim [] = $entity->getSku();   
            }
        }
        return $onedim;
    }

    public function removeRemovedSKU($sku, $customerid, $salespersonusername) {
        //first find the record
        $record = $this->findCheckbox($sku, $customerid, $salespersonusername);
        if (!empty($record)) {
            $record->setChecked(false);
        } else {
            $this->logger->info("Checkbox record not found to remove. SKU: " . $sku . " CUSTOMERID: " . $customerid . " SALESPERSONUSERNAME: " . $salespersonusername);
        }
        //$dql = "SELECT c FROM VehicleCatalogue\Model\Car c WHERE c.id = ?1";
        //$audi = $em->createQuery($dql)
        //->setParameter(1, array("name" => "Audi A8", "year" => 2010))
        //->getSingleResult();
        $this->checkboxrepository->removeCheckbox($sku, $customerid, $salespersonusername);
    }

    protected function findCheckbox($sku, $customerid, $salespersonusername) {
        return $this->checkboxrepository->findCheckbox($sku, $customerid, $salespersonusername);
    }

    protected function findUser($username) {
        return $this->userrepository->findUser($username);
    }

}
