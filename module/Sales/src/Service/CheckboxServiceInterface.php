<?php

namespace Sales\Service;

/**
 *
 * @author jasonpalmer
 */
interface CheckboxServiceInterface {
    
    public function addRemovedID($sku, $customerid, $salespersonusername);
    
    public function removeRemovedID($sku, $customerid, $salespersonusername);
    
    public function getRemovedIDS($customerid, $salespersonusername);
    
}
