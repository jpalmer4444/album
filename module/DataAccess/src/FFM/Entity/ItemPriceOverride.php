<?php

namespace DataAccess\FFM\Entity;

use DataAccess\FFM\Entity\PostFormBinder;
use DataAccess\FFM\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\ItemPriceOverrideRepositoryImpl")
 * @ORM\Table(name="item_price_override")
 */
class ItemPriceOverride extends PostFormBinder {
    
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
     * @ORM\ManyToOne(targetEntity="Product", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="product", referencedColumnName="id")
     */
    protected $product;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $retail;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $overrideprice;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $_active;
    
    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $_created;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="customerid", referencedColumnName="id")
     */
    protected $_customerid;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="salesperson", referencedColumnName="username")
     */
    protected $_salesperson;

    /*
     * Hydration
     */

    public function exchangeArray($data) {
        $this->overrideprice = (isset($data['overrideprice'])) ? $data['overrideprice'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /*
     * Accessors and Mutators.
     */

    public function getId() {
        return $this->id;
    }
    
    public function getRetail() {
        return $this->retail;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getCustomer() {
        return $this->_customerid;
    }

    public function getOverrideprice() {
        return $this->overrideprice;
    }

    public function getActive() {
        return $this->_active;
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
    
    public function setRetail($retail) {
        $this->retail = $retail;
        return $this;
    }

    public function setProduct($product) {
        $this->product = $product;
        return $this;
    }

    public function setCustomer($customer) {
        $this->_customerid = $customer;
        return $this;
    }

    public function setOverrideprice($overrideprice) {
        $this->overrideprice = $overrideprice;
        return $this;
    }

    public function setActive($active) {
        $this->_active = $active;
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
