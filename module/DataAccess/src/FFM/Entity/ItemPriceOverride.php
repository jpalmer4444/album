<?php

namespace DataAccess\FFM\Entity;

use DataAccess\FFM\Entity\User;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="item_price_override")
 */
class ItemPriceOverride extends PostFormBinder {
    
    public function __construct()
    {
        $this->_created=new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="sku", type="string", length=25)
     */
    protected $sku;
    /**
     * @ORM\Column(name="comment", type="string", length=255)
     */
    protected $comment;
    /**
     * @ORM\Column(name="option", type="string", length=255)
     */
    protected $option;
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
     * @ORM\Column(name="customerid", type="integer")
     */
    protected $_customerid;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="salesperson", referencedColumnName="username")
     */
    protected $_salesperson;

    /*
     * Hydration
     */

    public function exchangeArray($data) {
        $this->sku = (isset($data['sku'])) ? $data['sku'] : null;
        $this->overrideprice = (isset($data['overrideprice'])) ? $data['overrideprice'] : null;
        $this->overrideprice = (isset($data['option'])) ? $data['option'] : null;
        $this->overrideprice = (isset($data['comment'])) ? $data['comment'] : null;
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
    
    public function getOption() {
        return $this->option;
    }
    
    public function getComment() {
        return $this->comment;
    }

    public function getSku() {
        return $this->sku;
    }

    public function getCustomerid() {
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
    
    public function setOption($option) {
        $this->option = $option;
        return $this;
    }
    
    public function setComment($comment) {
        $this->comment = $comment;
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
