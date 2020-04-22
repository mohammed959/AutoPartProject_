<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:08 ص
 */

class OrderItem
{

    private $i;
    private $order_id;
    private $part_id;
    private $quantity;

    /**
     * OrderItem constructor.
     * @param $i
     * @param $order_id
     * @param $part_id
     * @param $quantity
     */
    public function __construct($part_id, $quantity, $i, $order_id)
    {
        $this->i = $i;
        $this->order_id = $order_id;
        $this->part_id = $part_id;
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
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
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
}