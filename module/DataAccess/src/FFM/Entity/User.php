<?php

class User
{
    /**
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
}
