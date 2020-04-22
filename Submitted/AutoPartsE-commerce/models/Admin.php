<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:39 م
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';

class Admin
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $joiningDate;
    public $salt;


    /**
     * Admin constructor.
     * @param $name
     * @param $email
     * @param string $joiningDate
     * @param int $id
     * @param string $password
     * @param string $salt
     */
    public function __construct($name, $email, $joiningDate = '', $id = -1, $password = '', $salt = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->joiningDate = $joiningDate;
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
    public function getJoiningDate()
    {
        return $this->joiningDate;
    }

    /**
     * @param mixed $joiningDate
     */
    public function setJoiningDate($joiningDate)
    {
        $this->joiningDate = $joiningDate;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }


    public function create()
    {
        $sql = "INSERT INTO `admin` (name, email, password, joining_date, salt) values(? , ? , ? , current_date(), ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->password, $this->salt]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `admin` SET `name`=?, `email`=? WHERE `admin_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->id]);
        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $sql = "DELETE FROM `admin` WHERE `admin_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }

    public function updatePassword()
    {
        $sql = "UPDATE `admin` SET `password`=?, `salt`=? WHERE `admin_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->password, $this->salt, $this->id]);
        if ($stmt->rowCount() == 0) return false;
        else return true;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `admin` WHERE `admin_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function emailExist($email)
    {
        $sql = "SELECT * FROM `admin` WHERE `email` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public static function byEmail($email)
    {
        $sql = "SELECT * FROM `admin` WHERE `email` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function fromRow($row)
    {
        $admin = new Admin($row['name'], $row['email'], $row['joining_date'], $row['admin_id']);
        $admin->setPassword($row['password']);
        $admin->setSalt($row['salt']);
        return $admin;
    }

}