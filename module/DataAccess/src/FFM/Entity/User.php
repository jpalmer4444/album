<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $username;
    
    /** 
     * Used internally by Doctrine - Do not touch or manipulate.
     * @ORM\Column(type="integer") 
     * @ORM\Version 
     */
    private $version;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $salespersonname;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $phone1;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $email;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $sales_attr_id;
    
    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastlogin;
    
    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $_created;
    
    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $_updated;
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getLastlogin() {
        return $this->lastlogin;
    }
    
    public function getSalespersonname() {
        return $this->salespersonname;
    }

    public function getSales_attr_id() {
        return $this->sales_attr_id;
    }
    
    public function getPhone1() {
        return $this->phone1;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getCreated() {
        return $this->_created;
    }
    
    public function getUpdated() {
        return $this->_updated;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setPhone1($phone1) {
        $this->phone1 = $phone1;
    }
    
    public function setLastlogin($lastlogin) {
        $this->lastlogin = $lastlogin;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setSalespersonname($salespersonname) {
        $this->salespersonname = $salespersonname;
    }

    public function setSales_attr_id($sales_attr_id) {
        $this->sales_attr_id = $sales_attr_id;
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
