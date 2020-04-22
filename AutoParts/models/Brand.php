<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:50 م
 */

class Brand
{

    private $id;
    private $name;
    private $manufacturerId;

    /**
     * Brand constructor.
     * @param $id
     * @param $name
     * @param $manufacturerId
     */
    public function __construct($name, $manufacturerId, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->manufacturerId = $manufacturerId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }

    /**
     * @param mixed $manufacturerId
     */
    public function setManufacturerId($manufacturerId)
    {
        $this->manufacturerId = $manufacturerId;
    }
}