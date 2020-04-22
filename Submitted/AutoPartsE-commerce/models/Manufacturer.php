<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:49 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';

class Manufacturer
{

    public $id;
    public $name;
    public $logoUrl;

    /**
     * Manufacturer constructor.
     * @param $name
     * @param string $logoUrl
     * @param int $id
     */
    public function __construct($name, $logoUrl = "", $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->logoUrl = $logoUrl;
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
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    /**
     * @param string $logoUrl
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
    }

    public static function fromRow($row)
    {
        return new Manufacturer($row['name'], $row['logo_url'], $row['manufacturer_id']);
    }

    public function create()
    {
        $sql = "INSERT INTO `manufacturer` (name, logo_url) values(? , ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->logoUrl]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }


    public function update()
    {
        $sql = "UPDATE `manufacturer` SET `name`=?, `logo_url`=? WHERE `manufacturer_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->logoUrl, $this->id]);

        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $sql = "DELETE FROM `manufacturer` WHERE `manufacturer_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `manufacturer` WHERE `manufacturer_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }


    public static function search($query, $page, $limit)
    {
        $query = "%$query%";
        $sql = "SELECT * FROM `manufacturer` WHERE `name` LIKE ? LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $manus = array();
        foreach ($rows as $row) {
            $manu = self::fromRow($row);
            array_push($manus, $manu);
        }
        return $manus;
    }

    public static function all($page, $limit)
    {
        $sql = "SELECT * FROM `manufacturer` LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $manus = array();
        foreach ($rows as $row) {
            $manu = self::fromRow($row);
            array_push($manus, $manu);
        }
        return $manus;
    }


    public static function nOfManus($query = '')
    {
        if ($query == '') {
            $sql = "SELECT COUNT(*) FROM `manufacturer` ";
        } else
            $sql = "SELECT COUNT(*) FROM `manufacturer` WHERE `name` LIKE ?";
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