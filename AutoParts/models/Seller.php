<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:42 م
 */

class Seller
{
    private $seller_id;
    private $name;
    private $email;
    private $password;
    private $location;

    /**
     * Seller constructor.
     * @param $name
     * @param $email
     * @param $password
     * @param $location
     */
    public function __construct($name, $email, $password, $location, $id = -1)
    {
        $this->seller_id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
    }


    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     * @param mixed $seller_id
     */
    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }


}