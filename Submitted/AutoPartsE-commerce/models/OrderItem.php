<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:08 ص
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class OrderItem
{

    public $i;
    public $orderId;
    public $partId;
    public $quantity;


    /**
     * OrderItem constructor.
     * @param $partId
     * @param $quantity
     * @param $i
     * @param $orderId
     */
    public function __construct($partId, $quantity, $i, $orderId)
    {
        $this->i = $i;
        $this->orderId = $orderId;
        $this->partId = $partId;
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getI()
    {
        return $this->i;
    }

    /**
     * @param mixed $i
     */
    public function setI($i)
    {
        $this->i = $i;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }


    public function create()
    {
        $sql = "INSERT INTO `order_item` (i, order_id, part_id, quantity) values(?, ?, ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->i, $this->orderId, $this->partId, $this->quantity]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `order_item` SET `quantity`=? WHERE `quantity`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->quantity]);
        return $stmt->rowCount() > 0;
    }


    public function delete()
    {
        $sql = "DELETE FROM `order_item` WHERE `order_id`=? AND `i`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->orderId, $this->i]);
        return $stmt->rowCount() > 0;
    }


    public static function byOrder($orderId)
    {
        $sql = "SELECT * FROM `order_item` WHERE `order_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderId]);
        if ($stmt->rowCount() == 0) return null;
        $rows = $stmt->fetchAll();
        $orderItems = [];
        foreach ($rows as $row) {
            $order_item = new OrderItem($row['part_id'], $row['quantity'], $row['i'], $row['order_id']);
            array_push($orderItems, $order_item);
        }
        return $orderItems;
    }
}