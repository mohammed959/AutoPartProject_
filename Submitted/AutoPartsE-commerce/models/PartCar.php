<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:10 ص
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class PartCar
{
    public $partId;
    public $brandId;
    public $startModel;
    public $endModel;

    /**
     * PartCar constructor.
     * @param $partId
     * @param $brandId
     * @param $startModel
     * @param $endModel
     */
    public function __construct($partId, $brandId, $startModel, $endModel)
    {
        $this->partId = $partId;
        $this->brandId = $brandId;
        $this->startModel = $startModel;
        $this->endModel = $endModel;
    }


    /**
     * @return mixed
     */
    public function getPartId()
    {
        return $this->partId;
    }

    /**
     * @param mixed $partId
     */
    public function setPartId($partId)
    {
        $this->partId = $partId;
    }

    /**
     * @return mixed
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * @param mixed $brandId
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;
    }

    /**
     * @return mixed
     */
    public function getStartModel()
    {
        return $this->startModel;
    }

    /**
     * @param mixed $startModel
     */
    public function setStartModel($startModel)
    {
        $this->startModel = $startModel;
    }

    /**
     * @return mixed
     */
    public function getEndModel()
    {
        return $this->endModel;
    }

    /**
     * @param mixed $endModel
     */
    public function setEndModel($endModel)
    {
        $this->endModel = $endModel;
    }




    public function create()
    {
        $sql = "INSERT INTO `part_car` (part_id, brand_id, start_model, end_model) values(? , ?, ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->partId, $this->brandId, $this->startModel, $this->endModel]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function delete()
    {
        $sql = "DELETE FROM `part_car` WHERE `part_id`=? AND `brand_id`=? AND `start_model`=? AND `end_model`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->partId, $this->brandId, $this->startModel, $this->endModel]);
        return $stmt->rowCount() > 0;
    }


    public static function carsOfPart($part)
    {
        $sql = "SELECT * FROM `part_car` WHERE `part_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$part]);
        $rows = $stmt->fetchAll();
        $part_car = array();
        foreach ($rows as $row) {
            $order = new PartCar($row['part_id'], $row['brand_id'], $row['start_model'], $row['end_model']);
            array_push($part_car, $order);
        }
        return $part_car;
    }
}
