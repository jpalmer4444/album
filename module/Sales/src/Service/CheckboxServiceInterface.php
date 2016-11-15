<?php

namespace Sales\Service;

/**
 *
 * @author jasonpalmer
 */
interface CheckboxServiceInterface {
    
    public function addRemovedSKU($sku, $customerid, $salespersonusername);
    
    public function removeRemovedSKU($sku, $customerid, $salespersonusername);
    
    public function getRemovedSKUS($customerid, $salespersonusername);
    
}
