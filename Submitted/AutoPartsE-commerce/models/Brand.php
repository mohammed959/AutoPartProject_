<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:50 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Manufacturer.php';

class Brand
{

    public $id;
    public $name;
    public $startModel;
    public $endModel;
    public $manufacturerId;


    /**
     * Brand constructor.
     * @param $name
     * @param $startModel
     * @param $endModel
     * @param $manufacturerId
     * @param int $id
     */
    public function __construct($name, $startModel, $endModel, $manufacturerId, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->startModel = $startModel;
        $this->endModel = $endModel;
        $this->manufacturerId = $manufacturerId;
    }

    private static function fromRow($row)
    {
        return new Brand($row['bname'], $row['start_model'], $row['end_model'], $row['manufacturer_id'], $row['brand_id']);
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

    public function manufacturer()
    {
        return Manufacturer::byId($this->manufacturerId);
    }

    public function create()
    {
        $sql = "INSERT INTO `brand` (manufacturer_id, bname, start_model, end_model) values(? , ?, ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->manufacturerId, $this->name, $this->startModel, $this->endModel]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }


    public function update()
    {
        $sql = "UPDATE `brand` SET `bname`=?, `start_model`=?, `end_model`=? WHERE `brand_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->startModel, $this->endModel, $this->id]);
        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $sql = "DELETE FROM `brand` WHERE `brand_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `brand` WHERE `brand_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        $brand = new Brand($row['bname'], $row['start_model'], $row['end_model'], $row['manufacturer_id'], $row['brand_id']);
        return $brand;
    }


    public static function byManufacturer($manufacturer)
    {
        $sql = "SELECT * FROM `brand` WHERE `manufacturer_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$manufacturer]);
        $rows = $stmt->fetchAll();
        $brands = array();
        foreach ($rows as $row) {
            $brand = self::fromRow($row);
            array_push($brands, $brand);
        }
        return $brands;
    }


    public static function search($query, $page, $limit)
    {
        $sql = "SELECT * FROM `brand` WHERE `bname` LIKE ? LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $brands = array();
        foreach ($rows as $row) {
            $brand = self::fromRow($row);
            array_push($brands, $brand);
        }
        return $brands;
    }

    public static function all($page, $limit)
    {
        $sql = "SELECT * FROM `brand` LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $brands = array();
        foreach ($rows as $row) {
            $brand = self::fromRow($row);
            array_push($brands, $brand);
        }
        return $brands;
    }


    public static function nOfCars($query = '')
    {
        if ($query == '') {
            $sql = "SELECT COUNT(*) FROM `brand` ";
        } else
            $sql = "SELECT COUNT(*) FROM `brand` WHERE `bname` LIKE ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        if ($query == '') {
            $stmt->execute();
        } else {
            $query = "%$query%";
            $stmt->execute([$query]);
        }

        return $stmt->fetchColumn(0);
    }

}