<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:51 م
 */

class Part
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $seller_id;
    private $category_id;

    /**
     * Part constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $price
     * @param $seller_id
     * @param $category_id
     */
    public function __construct($name, $description, $price, $seller_id, $category_id, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->seller_id = $seller_id;
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getPartId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setPartId($id)
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

}