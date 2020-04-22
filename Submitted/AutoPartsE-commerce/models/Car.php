<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:50 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';

class Car
{

    public $brandId;
    public $model;


    /**
     * Car constructor.
     * @param $model
     * @param $brandId
     */
    public function __construct($model, $brandId)
    {
        $this->brandId = $brandId;
        $this->model = $model;
    }

    /**
     * @return int
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * @param int $brandId
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;
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


    public function create()
    {
        $sql = "INSERT INTO `car` (brand_id, model) values(? , ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->brandId, $this->model]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function delete()
    {
        $sql = "DELETE FROM `car` WHERE `brand_id`=? AND `model` = ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->brandId, $this->model]);
        if ($stmt->rowCount() == 0) return false;
        else return true;
    }

    public static function exists($brandId, $model)
    {
        $sql = "SELECT * FROM `car` WHERE `brand_id` =? AND `model`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brandId, $model]);
        return $stmt->rowCount() > 0;
    }


    public static function byBrand($brandId)
    {
        $sql = "SELECT * FROM `car` WHERE `brand_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brandId]);
        $rows = $stmt->fetchAll();
        $cars = array();
        foreach ($rows as $row) {
            $car = new Car($row['model'], $row['brand_id']);
            array_push($cars, $car);
        }
        return $cars;
    }

}