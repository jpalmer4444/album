<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl")
 * @ORM\Table(name="products")
 */
class Product {
    
    public function __construct()
    {
        $this->_created=new DateTime();
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
    protected $product;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $comment;
    
    /**
     * @ORM\Column(name="`option`", type="string", length=255, nullable=true)
     */
    protected $option;
    
    /** 
     * @ORM\Column(type="integer")
     */
    private $qty;
    
    /**
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="integer")
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
    
    public function getId() {
        return $this->id;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getOption() {
        return $this->option;
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

    public function getCreated() {
        return $this->_created;
    }
    
    public function getUpdated() {
        return $this->_updated;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    public function setProduct($product) {
        $this->product = $product;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    public function setOption($option) {
        $this->option = $option;
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

    public function setCreated($_created) {
        $this->_created = $_created;
        return $this;
    }

    public function setUpdated($_updated) {
        $this->_updated = $_updated;
        return $this;
    }
    
}
