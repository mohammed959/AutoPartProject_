<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:51 م
 */

class Category
{

    private $id;
    private $name;
    private $parent_id;

    /**
     * Category constructor.
     * @param $id
     * @param $name
     * @param $parent_id
     */
    public function __construct($name, $parent_id, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parent_id = $parent_id;
    }

    /**
     * @return mixed
     */
    public function getCatId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setCatId($id)
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
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }


}