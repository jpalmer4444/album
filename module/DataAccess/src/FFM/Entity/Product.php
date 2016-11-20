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
    protected $productname;
    
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
    
    /**
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;
    
    /**
     * @ORM\Column(name="saturdayenabled", type="boolean")
     */
    protected $saturdayenabled;
    
    public function exchangeArray($data) {
        $this->sku = (isset($data['sku'])) ? $data['sku'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->option = (isset($data['option'])) ? $data['option'] : null;
        $this->productname = (isset($data['product'])) ? $data['product'] : null;
        $this->qty = (isset($data['qty'])) ? $data['qty'] : null;
        $this->retail = (isset($data['retail'])) ? $data['retail'] : null;
        $this->saturdayenabled = (isset($data['saturdayenabled'])) ? $data['saturdayenabled'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->uom = (isset($data['uom'])) ? $data['uom'] : null;
        $this->wholesale = (isset($data['wholesale'])) ? $data['wholesale'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function getId() {
        return $this->id;
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


    
}
