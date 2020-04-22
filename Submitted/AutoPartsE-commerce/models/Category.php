<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:51 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';

class Category
{

    public $id;
    public $name;
    public $parentId;


    /**
     * Category constructor.
     * @param $name
     * @param $parent_id
     * @param int $id
     */
    public function __construct($name, $parent_id, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parent_id;
    }

    public static function fromRow($row)
    {
        return new Category($row['cname'], $row['parent_id'], $row['cat_id']);
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
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function parent()
    {
        return self::byId($this->parentId);
    }

    public function create()
    {
        $sql = "INSERT INTO `category` (cname, parent_id) values(? , ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->parentId]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `category` SET `cname`=?, `parent_id`=? WHERE `cat_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->parentId, $this->id]);
        return $stmt->rowCount() > 0;
    }


    public function delete()
    {
        $sql = "DELETE FROM `category` WHERE `cat_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function byId($id)
    {
        $sql = "SELECT * FROM `category` WHERE `cat_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();

        return self::fromRow($row);
    }


    public static function byParent($parentId, $onlyId = false)
    {
        if ($onlyId) {
            $sql = "SELECT `cat_id` FROM `category` WHERE `parent_id` =?";
        } else {
            $sql = "SELECT * FROM `category` WHERE `parent_id` =?";
        }
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$parentId]);
        $rows = $stmt->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            if ($onlyId) {
                $category = $row['cat_id'];
            } else {
                $category = self::fromRow($row);
            }
            array_push($categories, $category);
        }
        return $categories;
    }

    public static function all($orderByParent)
    {
        if ($orderByParent)
            $sql = "SELECT * FROM `category` ORDER BY `parent_id`";
        else
            $sql = "SELECT * FROM `category`";

        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            $category = self::fromRow($row);
            array_push($categories, $category);
        }
        return $categories;
    }

    public static function search($query)
    {
        $query = "%$query%";
        $sql = "SELECT * FROM `category` WHERE `cname` LIKE ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query]);
        $rows = $stmt->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            $category = self::fromRow($row);
            array_push($categories, $category);
        }
        return $categories;
    }

    public static function subcategoriesIds($parentId)
    {
        $result = array();
        $cats = self::byParent($parentId, true);
        $result = array_merge($result, $cats);
        foreach ($cats as $cat) {
            $result = array_merge($result, self::subcategoriesIds($cat));
        }
        return $result;
    }

}