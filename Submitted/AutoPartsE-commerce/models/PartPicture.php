<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:12 ص
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class PartPicture
{

    public $id;
    public $partId;
    public $url;

    /**
     * PartPicture constructor.
     * @param $partId
     * @param $url
     * @param int $id
     */
    public function __construct($partId, $url, $id = -1)
    {
        $this->id = $id;
        $this->partId = $partId;
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function create()
    {
        $sql = "INSERT INTO `part_picture` (part_id, url) values(? , ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->partId, $this->url]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function delete()
    {
        $sql = "DELETE FROM `part_picture` WHERE `picture_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `part_picture` WHERE `picture_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        $part_picture = new PartPicture($row['part_id'], $row['url'], $id);
        return $part_picture;
    }


    public static function byPart($partId)
    {
        $sql = "SELECT * FROM `part_picture` WHERE `part_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$partId]);
        $rows = $stmt->fetchAll();
        $part_pictures = array();
        foreach ($rows as $row) {
            $part_picture = new PartPicture($row['part_id'], $row['url'], $row['picture_id']);
            array_push($part_pictures, $part_picture);
        }
        return $part_pictures;
    }
}