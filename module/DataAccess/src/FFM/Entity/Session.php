<?php

namespace DataAccess\FFM\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity(repositoryClass="SessionRepository")
 * @ORM\Table(name="session")
 */
class Session {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $modified;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $lifetime;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $data;
    
}
