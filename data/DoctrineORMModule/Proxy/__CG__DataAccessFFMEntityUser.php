<?php

namespace DoctrineORMModule\Proxy\__CG__\DataAccess\FFM\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \DataAccess\FFM\Entity\User implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'username', '' . "\0" . 'DataAccess\\FFM\\Entity\\User' . "\0" . 'version', 'password', 'salespersonname', 'phone1', 'email', 'sales_attr_id', 'lastlogin', '_created', '_updated', 'session_id'];
        }

        return ['__isInitialized__', 'username', '' . "\0" . 'DataAccess\\FFM\\Entity\\User' . "\0" . 'version', 'password', 'salespersonname', 'phone1', 'email', 'sales_attr_id', 'lastlogin', '_created', '_updated', 'session_id'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getSessionId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSessionId', []);

        return parent::getSessionId();
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getUsername();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsername', []);

        return parent::getUsername();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastlogin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastlogin', []);

        return parent::getLastlogin();
    }

    /**
     * {@inheritDoc}
     */
    public function getSalespersonname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSalespersonname', []);

        return parent::getSalespersonname();
    }

    /**
     * {@inheritDoc}
     */
    public function getSales_attr_id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSales_attr_id', []);

        return parent::getSales_attr_id();
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone1()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone1', []);

        return parent::getPhone1();
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', []);

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdated', []);

        return parent::getUpdated();
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', []);

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setSessionId($sessionId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSessionId', [$sessionId]);

        return parent::setSessionId($sessionId);
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone1($phone1)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone1', [$phone1]);

        return parent::setPhone1($phone1);
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', [$password]);

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function setLastlogin($lastlogin)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastlogin', [$lastlogin]);

        return parent::setLastlogin($lastlogin);
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function setUsername($username)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsername', [$username]);

        return parent::setUsername($username);
    }

    /**
     * {@inheritDoc}
     */
    public function setSalespersonname($salespersonname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSalespersonname', [$salespersonname]);

        return parent::setSalespersonname($salespersonname);
    }

    /**
     * {@inheritDoc}
     */
    public function setSales_attr_id($sales_attr_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSales_attr_id', [$sales_attr_id]);

        return parent::setSales_attr_id($sales_attr_id);
    }

    /**
     * {@inheritDoc}
     */
    public function setCreated($created)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreated', [$created]);

        return parent::setCreated($created);
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdated($updated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdated', [$updated]);

        return parent::setUpdated($updated);
    }

}
