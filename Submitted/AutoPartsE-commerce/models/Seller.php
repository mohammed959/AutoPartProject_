<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:42 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';


class Seller
{


    public static $ACTIVE = 1;
    public static $BLOCKED = 2;
    public static $WAITING = 3;

    public $id;
    public $name;
    public $email;
    public $password;
    public $location;
    public $registrationDate;
    public $status;
    public $certificate;
    public $salt;


    /**
     * Seller constructor.
     * @param $name
     * @param $email
     * @param $location
     * @param $certificate
     * @param string $registrationDate
     * @param int $id
     * @param int $status
     * @param string $password
     * @param int $salt
     */
    public function __construct($name, $email, $location, $certificate, $registrationDate = '', $id = -1, $status = 1, $password = '', $salt = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
        $this->registrationDate = $registrationDate;
        $this->certificate = $certificate;
        $this->status = $status;
        $this->salt = $salt;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param mixed $registrationDate
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param mixed $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }


    public static function fromRow($row)
    {
        return new Seller($row['sname'], $row['email'], $row['location'], $row['certification_url'], $row['registration_time'], $row['seller_id'], $row['status'], $row['password'], $row['salt']);
    }


    public function create()
    {
        $sql = "INSERT INTO `seller` (sname, email, password, location,certification_url, registration_time, status, salt) values(?, ? , ? , ? ,? , now(), ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->password, $this->location, $this->certificate, $this->status, $this->salt]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `seller` SET `sname`=?, `email`=?, `location`=?, `status`=? WHERE `seller_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->location, $this->status, $this->id]);
        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $sql = "DELETE FROM `seller` WHERE `seller_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }

    public function updatePassword()
    {
        $sql = "UPDATE `seller` SET `password`=? WHERE `seller_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->password, $this->id]);
        if ($stmt->rowCount() == 0) return false;
        else return true;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `seller` WHERE `seller_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function byEmail($email)
    {
        $sql = "SELECT * FROM `seller` WHERE `email` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function nOfSellers($query = '')
    {
        if ($query == '') {
            $sql = "SELECT COUNT(*) FROM `seller` WHERE status != " . self::$WAITING;
        } else {
            $sql = "SELECT COUNT(*) FROM `seller` WHERE `sname` LIKE ? AND status != " . self::$WAITING;
        }
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        if ($query == '') {
            $stmt->execute();
        } else {
            $stmt->execute([$query]);
        }
        return $stmt->fetchColumn(0);
    }


    public static function all($page, $limit)
    {
        $sql = "SELECT * FROM `seller` WHERE `status` != " . self::$WAITING . " LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $sellers = array();
        foreach ($rows as $row) {
            $seller = self::fromRow($row);
            array_push($sellers, $seller);
        }
        return $sellers;
    }

    public static function search($query)
    {
        $query = "%$query%";
        $sql = "SELECT * FROM `seller` WHERE `sname` LIKE ? status != " . self::$WAITING;
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query]);
        $rows = $stmt->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $user = self::fromRow($row);
            array_push($users, $user);
        }
        return $users;
    }

    public static function getUnactivated($page, $limit)
    {
        $sql = "SELECT * FROM `seller` WHERE `status` = " . self::$WAITING . " LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $user = self::fromRow($row);
            array_push($users, $user);
        }
        return $users;
    }

    public static function getNumberOfUnactivated()
    {
        $sql = "SELECT COUNT(*) FROM `seller` WHERE `status` = " . self::$WAITING;
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}