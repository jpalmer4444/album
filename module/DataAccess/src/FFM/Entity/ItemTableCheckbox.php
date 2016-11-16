<?php

namespace DataAccess\FFM\Entity;

use DataAccess\FFM\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ItemTableCheckboxRepository")
 * @ORM\Table(name="item_table_checkbox")
 */
class ItemTableCheckbox extends PostFormBinder {
    
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
     * @ORM\Column(name="sku", type="string", length=25)
     */
    protected $sku;
    
    /**
     * @ORM\Column(name="product", type="string", length=255)
     */
    protected $product;
    
    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $_created;

    /**
     * @ORM\Column(name="customerid", type="integer")
     */
    protected $_customerid;

    /**
     * @ORM\Column(name="salesperson", type="string", length=100)
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="salesperson", referencedColumnName="username")
     */
    protected $_salesperson;
    
     /**
     * @ORM\Column(name="checked", type="boolean")
     */
    protected $checked;
    
    public function getId() {
        return $this->id;
    }

    public function getSku() {
        return $this->sku;
    }
    
    public function getChecked() {
        return $this->checked;
    }

    public function getCustomerid() {
        return $this->_customerid;
    }

    public function getCreated() {
        return $this->_created;
    }

    public function getSalesperson() {
        return $this->_salesperson;
    }

    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setChecked($checked) {
        $this->checked = $checked;
        return $this;
    }

    public function setCustomerid($customerid) {
        $this->_customerid = $customerid;
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
