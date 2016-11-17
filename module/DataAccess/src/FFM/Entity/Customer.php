<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/** 
 * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl")
 * @ORM\Table(name="customers")
 */
class Customer
{
    
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
     * @ORM\Column(type="string", length=100)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $company;
    
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

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getCompany() {
        return $this->company;
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

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }
    
    public function setCreated($created) {
        $this->_created = $created;
        return $this;
    }
    
    public function setUpdated($updated) {
        $this->_updated = $updated;
        return $this;
    }

}
