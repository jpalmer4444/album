<?php

namespace DataAccess\FFM\Entity;

use DataAccess\FFM\Entity\User;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="row_plus_items_page")
 */
class RowPlusItemsPage extends PostFormBinder {
    
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
     * @ORM\Column(name="productname", type="string", length=255)
     */
    protected $product;

    /**
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $comment;

    /**
     * @ORM\Column(type="string")
     */
    protected $option;

    /**
     * @ORM\Column(type="integer")
     */
    protected $qty;

    /**
     * @ORM\Column(type="integer")
     */
    protected $wholesale;

    /**
     * @ORM\Column(type="integer")
     */
    protected $retail;

    /**
     * @ORM\Column(type="integer")
     */
    protected $overrideprice;

    /**
     * @ORM\Column(type="string")
     */
    protected $uom;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $_active;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $saturdayenabled;

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
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->option = (isset($data['option'])) ? $data['option'] : null;
        $this->overrideprice = (isset($data['overrideprice'])) ? $data['overrideprice'] : null;
        $this->product = (isset($data['product'])) ? $data['product'] : null;
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

    /*
     * Accessors and Mutators.
     */

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

    public function getOption() {
        return $this->option;
    }

    public function getQty() {
        return $this->qty;
    }

    public function getWholesale() {
        return $this->wholesale;
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

    public function getStatus() {
        return $this->status;
    }

    public function getActive() {
        return $this->_active;
    }

    public function getSaturdayenabled() {
        return $this->saturdayenabled;
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

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }

    public function setSaturdayenabled($saturdayenabled) {
        $this->saturdayenabled = $saturdayenabled;
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
