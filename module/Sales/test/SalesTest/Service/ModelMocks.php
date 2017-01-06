<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SalesTest\Service;

use DataAccess\FFM\Entity\Customer;
use DataAccess\FFM\Entity\Product;
use DataAccess\FFM\Entity\RowPlusItemsPage;
use DataAccess\FFM\Entity\User;
use DataAccess\FFM\Entity\UserProduct;

/**
 * Description of ModelMocks
 *
 * @author jasonpalmer
 */
class ModelMocks {
   
    public static function getMockUser($append, $date, $id){
        $user_mock = new User();
        $user_mock->setCreated($date);
        $user_mock->setEmail("mock" . $append . "@email.com");
        $user_mock->setLastlogin($date);
        $user_mock->setPassword("password");
        $user_mock->setPhone1("999-999-9999");
        $user_mock->setSales_attr_id($id);
        $user_mock->setSalespersonname("Mock Salesperson " . $append);
        $user_mock->setUpdated($date);
        $user_mock->setUsername("Mock username " . $append);
        return $user_mock;
    }
    
    public static function getMockCustomer($append, $date, $id){
        $customer_mock = new Customer();
        $customer_mock->setCompany("Mock Company " . $append);
        $customer_mock->setCreated($date);
        $customer_mock->setEmail("mock" . $append . "@email.com");
        $customer_mock->setId($id);
        $customer_mock->setName("Mocked Name " . $append);
        $customer_mock->setUpdated($date);
        $customer_mock->setVersion(1);
        return $customer_mock;
    }
    
    public static function getMockProduct($append, $date, $id, $sku, $status, $customer){
        $product_mock = new Product();
        $product_mock->setDescription("Description " . $append);
        $product_mock->setId($id);
        $product_mock->setProductname("Product Name " . $append);
        $product_mock->setQty(1);
        $product_mock->setRetail(9.99);
        $product_mock->setSaturdayenabled(TRUE);
        $product_mock->setSku($sku);
        $product_mock->setStatus($status);
        $product_mock->setUom("lb");
        $userProducts = ModelMocks::getUserProducts($customer, $date);
        foreach($userProducts as $userProduct){
            $userProduct->setProduct($product_mock);
        }
        $product_mock->setUserProducts($userProducts);
        $product_mock->setVersion(1);
        $product_mock->setWholesale(5.99);
        $product_mock->set_created($date);
        $product_mock->set_updated($date);
        return $product_mock;
    }
    
    public static function getUserProducts(Customer $customer, $date){
        $userProducts = array();
        $userProduct = new UserProduct();
        $userProduct->setComment("comment by " . $customer->getName());
        $userProduct->setCreated($date);
        $userProduct->setCustomer($customer);
        $userProduct->setOption("option by " . $customer->getName());
        $userProduct->setUpdated($date);
        $userProduct->setVersion(1);
        return $userProducts;
    }
    
    public static function getRowPlusItemsPage(Customer $customer, User $salesperson, $id, $sku, $date, $logger){
        $rowplusitemspage = new RowPlusItemsPage();
        $rowplusitemspage->setActive(TRUE);
        $rowplusitemspage->setComment("comment by " . $customer->getName());
        $rowplusitemspage->setCreated($date);
        $rowplusitemspage->setCustomer($customer);
        $rowplusitemspage->setDescription("Description");
        $rowplusitemspage->setId($id);
        $rowplusitemspage->setOverrideprice(9.99);
        $rowplusitemspage->setProductname("Mock Product Name ");
        $rowplusitemspage->setSalesperson($salesperson);
        $rowplusitemspage->setSku($sku);
        $rowplusitemspage->setStatus(TRUE);
        $rowplusitemspage->setUom("lb");
        $rowplusitemspage->setVersion(1);
        return $rowplusitemspage;
    }
    
    public static function getMyAuthStorageMock(){
        
    }
    
}
