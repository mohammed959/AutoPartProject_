<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:50 م
 */

class Car
{

    private $brand_id;
    private $model;

    /**
     * Car constructor.
     * @param $brand_id
     * @param $model
     */
    public function __construct($model, $brand_id)
    {
        $this->brand_id = $brand_id;
        $this->model = $model;
    }

    /**
     * @return int
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * @param int $brand_id
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