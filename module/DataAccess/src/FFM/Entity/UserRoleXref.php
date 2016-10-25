<?php

namespace DataAccess\FFM\Entity;
use Doctrine\ORM\Mapping as ORM;

/** 
  * @ORM\Entity 
  * @ORM\Table(name="user_role_xref")
  */
class UserRoleXref
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $username;
    
    public function getRole() {
        return $this->role;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setUsername($username) {
        $this->username = $username;
    }


}
