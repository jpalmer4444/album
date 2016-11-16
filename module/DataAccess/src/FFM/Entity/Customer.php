<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity(repositoryClass="CustomerRepository")
 * @ORM\Table(name="customers")
 */
class Customer
{
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

}
