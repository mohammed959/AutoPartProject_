<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:52 م
 */

class Review
{
    private $part_id;
    private $user_id;
    private $rating;
    private $comment;

    /**
     * Review constructor.
     * @param $part_id
     * @param $user_id
     * @param $rating
     * @param $comment
     */
    public function __construct($part_id, $user_id, $rating, $comment)
    {
        $this->part_id = $part_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
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
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}