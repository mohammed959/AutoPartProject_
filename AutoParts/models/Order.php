<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:52 م
 */

class Order
{
    private $id;
    private $user_id;
    private $seller_id;
    private $status;

    /**
     * Order constructor.
     * @param $id
     * @param $user_id
     * @param $seller_id
     * @param $status
     */
    public function __construct($user_id, $seller_id, $status, $id = -1)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->seller_id = $seller_id;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setOrderId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     * @param mixed $seller_id
     */
    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
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
}