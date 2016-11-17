<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;


 /** 
  * @ORM\Entity(repositoryClass="DataAccess\FFM\Entity\Repository\Impl\RoleRepositoryImpl")
  * @ORM\Table(name="roles")
  */
class Role
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;
    
    public function getRole() {
        return $this->role;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setDescription($description) {
        $this->description = $description;
    }


}
