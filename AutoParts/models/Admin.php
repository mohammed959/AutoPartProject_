<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:39 م
 */

class Admin
{
    private $admin_id;
    private $name;
    private $email;
    private $password;

    /**
     * Admin constructor.
     * @param $name
     * @param $email
     * @param $password
     * @param int $id
     */
    public function __construct($name, $email, $password, $id = -1)
    {
        $this->admin_id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }


    /**
     * @return mixed
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param mixed $admin_id
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
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


}