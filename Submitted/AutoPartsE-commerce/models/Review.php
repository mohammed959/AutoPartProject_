<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:52 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class Review
{
    public $partId;
    public $userId;
    public $rating;
    public $comment;
    public $time;


    /**
     * Review constructor.
     * @param $partId
     * @param $userId
     * @param $rating
     * @param $comment
     * @param $time
     */
    public function __construct($partId, $userId, $rating, $comment, $time = -1)
    {
        $this->partId = $partId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->time = $time;
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
        $sql = "INSERT INTO `review` (part_id, user_id, rating, comment,ctime) values(? , ?, ?, ?, now())";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->partId, $this->userId, $this->rating, $this->comment]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `review` SET `comment`=?, `rating`=? WHERE `part_id`=? AND `user_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->comment, $this->rating, $this->partId, $this->userId]);
        return $stmt->rowCount() > 0;
    }


    public function delete()
    {
        $sql = "DELETE FROM `review` WHERE `part_id`=? AND `user_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->partId, $this->userId]);
        return $stmt->rowCount() > 0;
    }


    public static function byUserPart($userId, $partId)
    {
        $sql = "SELECT * FROM `review` WHERE `user_id` =? AND `part_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $partId]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        $review = new Review($row['part_id'], $row['user_id'], $row['rating'], $row['comment'], $row['ctime']);
        return $review;
    }


    public static function byPart($partId)
    {
        $sql = "SELECT * FROM `review` WHERE `part_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$partId]);
        if ($stmt->rowCount() == 0) return array();
        $rows = $stmt->fetchAll();
        $reviews = array();
        foreach ($rows as $row) {
            $review = new Review($row['part_id'], $row['user_id'], $row['rating'], $row['comment'], $row['ctime']);
            array_push($reviews, $review);
        }
        return $reviews;
    }

    public static function numberOfReviews($partId)
    {
        $sql = "SELECT COUNT(*) FROM `review` WHERE `part_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$partId]);
        if ($stmt->rowCount() == 0) return null;

        return $stmt->fetchColumn(0);
    }

    public static function byUser($userId, $page, $limit)
    {
        $sql = "SELECT * FROM `review` WHERE `user_id` =? LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $reviews = array();
        foreach ($rows as $row) {
            $review = new Review($row['part_id'], $row['user_id'], $row['rating'], $row['comment'], $row['ctime']);
            array_push($reviews, $review);
        }
        return $reviews;
    }

    public static function numberOfUserReviews($userId)
    {
        $sql = "SELECT COUNT(*) FROM `review` WHERE `user_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public static function bySeller($sellerId, $page = -1, $limit = -1)
    {
        $sql = "SELECT * FROM `review` JOIN `part` WHERE `review`.`part_id`=`part`.`part_id` AND `seller_id` =?";
        if ($page != -1) {
            $sql .= " LIMIT ? OFFSET ?";
        }
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        if ($page == -1)
            $stmt->execute([$sellerId]);
        else
            $stmt->execute([$sellerId, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $reviews = array();
        foreach ($rows as $row) {
            $review = new Review($row['part_id'], $row['user_id'], $row['rating'], $row['comment'], $row['ctime']);
            array_push($reviews, $review);
        }
        return $reviews;
    }

    public static function numberOfSellerReviews($sellerId)
    {
        $sql = "SELECT COUNT(*) FROM `review` JOIN `part` WHERE `review`.`part_id`=`part`.`part_id` AND `seller_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sellerId]);
        return $stmt->fetchColumn();
    }
}