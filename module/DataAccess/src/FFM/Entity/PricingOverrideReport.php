<?php

namespace DataAccess\FFM\Entity;

use DataAccess\FFM\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="PricingOverrideReportRepository")
 * @ORM\Table(name="pricing_override_report")
 */
class PricingOverrideReport {
    
    public function __construct()
    {
        $this->_created=new DateTime();
    }
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * Used internally by Doctrine - Do not touch or manipulate.
     * @ORM\Column(type="integer") 
     * @ORM\Version 
     */
    private $version;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $product;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $comment;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $uom;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $sku;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $retail;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $overrideprice;
    
    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $_created;
    
    /**
     * @ORM\Column(name="customerid", type="integer")
     */
    protected $_customerid;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="salesperson", referencedColumnName="username")
     */
    protected $_salesperson;
    
    public function getId() {
        return $this->id;
    }

    public function getSku() {
        return $this->sku;
    }

    public function getProduct() {
        return $this->product;
    }
    
    public function getCustomerid() {
        return $this->_customerid;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getRetail() {
        return $this->retail;
    }

    public function getOverrideprice() {
        return $this->overrideprice;
    }

    public function getUom() {
        return $this->uom;
    }

    public function getCreated() {
        return $this->_created;
    }

    public function getSalesperson() {
        return $this->_salesperson;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }
    
    public function setCustomerid($customerid) {
        $this->_customerid = $customerid;
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

    public function setRetail($retail) {
        $this->retail = $retail;
        return $this;
    }

    public function setOverrideprice($overrideprice) {
        $this->overrideprice = $overrideprice;
        return $this;
    }

    public function setUom($uom) {
        $this->uom = $uom;
        return $this;
    }

    public function setCreated($created) {
        $this->_created = $created;
        return $this;
    }

    public function setSalesperson($salesperson) {
        $this->_salesperson = $salesperson;
        return $this;
    }
    
}
