<?php

namespace DataAccess\FFM\Entity;
use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity
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
     * @ORM\Column(type="string")
     */
    protected $password;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $sales_attr_id;
    
    public function getUsername() {
        return $this->username;
    }

    public function getSales_attr_id() {
        return $this->sales_attr_id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setSales_attr_id($sales_attr_id) {
        $this->sales_attr_id = $sales_attr_id;
    }
    
    public function getPassword() {
        return $this->password;
    }



}
