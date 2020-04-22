<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:52 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class Order
{
    public $id;
    public $userId;
    public $sellerId;
    public $status;
    public $time;


    /**
     * Order constructor.
     * @param $userId
     * @param $sellerId
     * @param $status
     * @param $time
     * @param int $id
     */
    public function __construct($userId, $sellerId, $status, $time = 0, $id = -1)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->sellerId = $sellerId;
        $this->status = $status;
        $this->time = $time;
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @param mixed $sellerId
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


    public function create()
    {
        $sql = "INSERT INTO `orders` (user_id,seller_id, otime, status) values(? , ?, now(), ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->userId, $this->sellerId, $this->status]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `orders` SET `status`=? WHERE `order_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->status, $this->id]);
        return $stmt->rowCount() > 0;
    }


    public function delete()
    {
        $sql = "DELETE FROM `orders` WHERE `order_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `orders` WHERE `order_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        $order = new Order($row['user_id'], $row['seller_id'], $row['status'], $row['otime'], $row['order_id']);
        return $order;
    }


    public static function byUser($userId)
    {
        $sql = "SELECT * FROM `orders` WHERE `user_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();
        $orders = array();
        foreach ($rows as $row) {
            $order = new Order($row['user_id'], $row['seller_id'], $row['status'], $row['otime'], $row['order_id']);
            array_push($orders, $order);
        }
        return $orders;
    }

    public static function bySeller($sellerId)
    {
        $sql = "SELECT * FROM `orders` WHERE `seller_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sellerId]);
        $rows = $stmt->fetchAll();
        $orders = array();
        foreach ($rows as $row) {
            $order = new Order($row['user_id'], $row['seller_id'], $row['status'], $row['otime'], $row['order_id']);
            array_push($orders, $order);
        }
        return $orders;
    }

    public static function top10Sellers()
    {
        $sql = "SELECT seller_id,COUNT(*) FROM  `orders` GROUP BY `orders`.`seller_id` LIMIT 10";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $sellers = array();
        foreach ($rows as $row) {
            array_push($sellers, $row['seller_id']);
        }
        return $sellers;
    }
}