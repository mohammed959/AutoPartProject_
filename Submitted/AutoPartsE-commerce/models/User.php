<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:47 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';

class User
{

    public static $ACTIVE = 1;
    public static $BLOCKED = 2;
    public static $WAITING = 3;


    public $id;
    public $name;
    public $email;
    public $password;
    public $registrationDate;
    public $status;
    public $salt;
    public $token;

    /**
     * User constructor.
     * @param $name
     * @param $email
     * @param string $registrationDate
     * @param int $id
     * @param int $status
     */
    public function __construct($name, $email, $registrationDate = '', $id = -1, $status = 1, $token = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->registrationDate = $registrationDate;
        $this->status = $status;
        $this->token = $token;
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
        $user = new User($row['name'], $row['email'], $row['registration_time'], $row['user_id'], $row['status']);
        $user->setPassword($row['password']);
        $user->setSalt($row['salt']);
        return $user;
    }

    public function create()
    {
        $sql = "INSERT INTO `user` (name, email, password, registration_time, status, salt, token) values(? , ? , ? , now(), ?, ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->password, $this->status, $this->salt, $this->token]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `user` SET `name`=?, `email`=?, `status`=? WHERE `user_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->email, $this->status, $this->id]);
        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $sql = "DELETE FROM `user` WHERE `user_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }

    public function updatePassword()
    {
        $sql = "UPDATE `user` SET `password`=?, salt=? WHERE `user_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->password, $this->salt, $this->id]);
        if ($stmt->rowCount() == 0) return false;
        else return true;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `user` WHERE `user_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function emailExist($email)
    {
        $sql = "SELECT * FROM `user` WHERE `email` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public static function byEmail($email)
    {
        $sql = "SELECT * FROM `user` WHERE `email` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

    public static function nOfUsers($query = '')
    {
        if ($query == '') {
            $sql = "SELECT COUNT(*) FROM `user`";
        } else {
            $query = "%$query%";
            $sql = "SELECT COUNT(*) FROM `user` WHERE `name` LIKE ?";
        }
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        if ($query == '')
            $stmt->execute();
        else
            $stmt->execute([$query]);
        return $stmt->fetchColumn(0);
    }


    public static function all($page, $limit)
    {
        $sql = "SELECT * FROM `user` LIMIT ? OFFSET ?";
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


    public static function search($query, $page, $limit)
    {
        $query = "%$query%";
        $sql = "SELECT * FROM `user` WHERE `name` LIKE ? LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $user = self::fromRow($row);
            array_push($users, $user);
        }
        return $users;
    }

    public static function byToken($token)
    {

        $sql = "SELECT * FROM `user` WHERE `token` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$token]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }

}