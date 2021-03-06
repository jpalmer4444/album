<?php

namespace DataAccess\FFM\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl")
 * @ORM\Table(name="products")
 */
class Product {

    public function __construct() {
        $this->_created = new DateTime();
        $this->userProducts = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * Used internally by Doctrine - Do not touch or manipulate.
     * @ORM\Column(type="integer") 
     * @ORM\Version 
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $productname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $wholesale;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $uom;

    /**
     * @ORM\Column(type="string", length=25)
     */
    protected $sku;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $retail;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $_created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $_updated;

    /**
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;

    /**
     * @ORM\Column(name="saturdayenabled", type="boolean")
     */
    protected $saturdayenabled;

    /**
     * @ORM\OneToMany(targetEntity="UserProduct", mappedBy="product")
     */
    private $userProducts;

    public function getId() {
        return $this->id;
    }

    public function getCustomerUserProduct($customer) {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("customer", $customer));
        return $this->getUserProducts()->matching($criteria);
    }

    public function getVersion() {
        return $this->version;
    }

    public function getProductname() {
        return $this->productname;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getQty() {
        return $this->qty;
    }

    public function getWholesale() {
        return $this->wholesale;
    }

    public function getUom() {
        return $this->uom;
    }

    public function getSku() {
        return $this->sku;
    }

    public function getRetail() {
        return $this->retail;
    }

    public function get_created() {
        return $this->_created;
    }

    public function get_updated() {
        return $this->_updated;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getSaturdayenabled() {
        return $this->saturdayenabled;
    }

    public function getUserProducts() {
        return $this->userProducts;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    public function setProductname($productname) {
        $this->productname = $productname;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setQty($qty) {
        $this->qty = $qty;
        return $this;
    }

    public function setWholesale($wholesale) {
        $this->wholesale = $wholesale;
        return $this;
    }

    public function setUom($uom) {
        $this->uom = $uom;
        return $this;
    }

    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }

    public function setRetail($retail) {
        $this->retail = $retail;
        return $this;
    }

    public function set_created($_created) {
        $this->_created = $_created;
        return $this;
    }

    public function set_updated($_updated) {
        $this->_updated = $_updated;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setSaturdayenabled($saturdayenabled) {
        $this->saturdayenabled = $saturdayenabled;
        return $this;
    }

    public function setUserProducts($userProducts) {
        $this->userProducts = $userProducts;
        return $this;
    }

}
