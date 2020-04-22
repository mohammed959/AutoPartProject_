<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:10 ص
 */

class PartCar
{
    private $part_id;
    private $brand_id;
    private $model;

    /**
     * PartCar constructor.
     * @param $part_id
     * @param $brand_id
     * @param $model
     */
    public function __construct($part_id, $brand_id, $model)
    {
        $this->part_id = $part_id;
        $this->brand_id = $brand_id;
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getPartId()
    {
        return $this->part_id;
    }

    /**
     * @param mixed $part_id
     */
    public function setPartId($part_id)
    {
        $this->part_id = $part_id;
    }

    /**
     * @return mixed
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * @param mixed $brand_id
     */
    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }





}