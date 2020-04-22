<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 24/10/18
 * Time: 11:51 م
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/DBConnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Seller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/PartPicture.php';


class Part
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $sellerId;
    public $categoryId;

    public $seller;
    public $category;
    public $pictures;

    /**
     * Part constructor.
     * @param $name
     * @param $description
     * @param $price
     * @param $sellerId
     * @param $categoryId
     * @param int $id
     */
    public function __construct($name, $description, $price, $sellerId, $categoryId, $id = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->sellerId = $sellerId;
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getPartId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setPartId($id)
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function category()
    {
        return Category::byId($this->categoryId);
    }

    public function seller()
    {
        return Seller::byId($this->sellerId);
    }


    public function create()
    {
        $sql = "INSERT INTO `part` (pname, description, price, seller_id, category_id) values(?, ?, ?, ?, ?)";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->description, $this->price, $this->sellerId, $this->categoryId]);
        if ($stmt->rowCount() == 0) return -1;
        else return $pdo->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE `part` SET `pname`=?, `description`=?, `price`=?, `category_id`=? WHERE `part_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->name, $this->description, $this->price, $this->categoryId, $this->id]);
        return $stmt->rowCount() > 0;
    }


    public function delete()
    {
        $sql = "DELETE FROM `part` WHERE `part_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    }


    public static function fromRow($row)
    {
        return new Part($row['pname'], $row['description'], $row['price'], $row['seller_id'], $row['category_id'], $row['part_id']);
    }

    public static function byId($id)
    {
        $sql = "SELECT * FROM `part` WHERE `part_id` =?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) return null;
        $row = $stmt->fetch();
        return self::fromRow($row);
    }


    public static function byCar($brand, $model, $page, $limit, $withPictures = true)
    {
        $sql = "SELECT * FROM `part` JOIN `part_car` WHERE `part`.`part_id` = `part_car`.`part_id` AND `brand_id`=? AND `start_model`<=? AND `end_model`>= ? ORDER BY `part`.`part_id` DESC LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brand, $model, $model, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }


    public static function byCarAndCategory($brand, $model, $category, $page, $limit, $withPictures = true)
    {
        $sql = "SELECT * FROM `part` JOIN `part_car` WHERE `part`.`part_id` = `part_car`.`part_id` AND `brand_id`=? AND `start_model`<=? AND `end_model`>= ? AND `category_id`=? ORDER BY `part`.`part_id` DESC LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brand, $model, $model, $category, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function byCategoriesAndCar($categories, $brand, $model, $page, $limit, $withPictures = true)
    {
        $sql = "SELECT * FROM `part`  JOIN `part_car`  WHERE  `part`.`part_id` = `part_car`.`part_id` AND `brand_id`=? AND `start_model`<=? AND `end_model`>= ? AND (";

        $ncats = count($categories);
        for ($i = 0; $i < $ncats; $i++) {
            $sql .= " `category_id`=? ";
            if ($i != $ncats - 1) $sql .= " OR ";
        }

        $sql .= ") ORDER BY `part`.`part_id` DESC LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);

        $params = array_merge([$brand, $model, $model], $categories, [$limit, ($page - 1) * $limit]);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function byCategories($categories, $page, $limit, $withPictures = true)
    {
        $sql = "SELECT * FROM `part` WHERE ";

        $ncats = count($categories);
        for ($i = 0; $i < $ncats; $i++) {
            $sql .= " `category_id`=? ";
            if ($i != $ncats - 1) $sql .= " OR ";
        }

        $sql .= " ORDER BY `part`.`part_id` DESC  LIMIT ? OFFSET ?";

        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);

        $categories = array_merge($categories, [$limit, ($page - 1) * $limit]);

        $stmt->execute($categories);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function allBySeller($seller, $withPictures = true)
    {
        $sql = "SELECT * FROM `part` WHERE `seller_id`=? ORDER BY `part`.`part_id` DESC";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$seller]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function bySeller($seller, $page, $limit, $withPictures = true)
    {
        $sql = "SELECT * FROM `part` WHERE `seller_id`=? ORDER BY `part`.`part_id` DESC  LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$seller, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withPictures)
                $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function searchWithCar($query, $brand, $model, $page, $limit, $withSeller = false, $withCategory = false, $withPictures = true)
    {
        $query = "%$query%";

        $sql = "SELECT * FROM `part` JOIN `part_car` " .
            ($withCategory ? ", `category`" : "") . ($withSeller ? ", `seller` " : "") .
            " WHERE  (`pname` LIKE ? OR `description` LIKE ?) AND `part`.`part_id` = `part_car`.`part_id` AND `brand_id`=? AND `start_model`<=? AND `end_model`>= ?  " . ($withSeller ? " AND `part`.`seller_id`=`seller`.`seller_id` " : "")
            . ($withCategory ? " AND `part`.`category_id` = `category`.`cat_id`" : "") .
            " ORDER BY `part`.`part_id` DESC  LIMIT ? OFFSET ?";


        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query, $query, $brand, $model, $model, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withCategory) $part->category = Category::fromRow($row);
            if ($withSeller) $part->seller = Seller::fromRow($row);
            if ($withPictures) $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }


    public static function search($query, $page, $limit, $withSeller = false, $withCategory = false, $withPictures = true)
    {
        $query = "%$query%";

        $sql = "SELECT * FROM `part` " .
            ($withCategory ? (" JOIN `category`" . ($withSeller ? ",`seller`" : "")) : ($withSeller ? "JOIN `seller` " : "")) .
            " WHERE (`pname` LIKE ? OR `description` LIKE ?)  " . ($withSeller ? " AND `part`.`seller_id`=`seller`.`seller_id` " : "")
            . ($withCategory ? " AND `part`.`category_id` = `category`.`cat_id`" : "") .
            " ORDER BY `part`.`part_id` DESC LIMIT ? OFFSET ?";


        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$query, $query, $limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withCategory) $part->category = Category::fromRow($row);
            if ($withSeller) $part->seller = Seller::fromRow($row);
            if ($withPictures) $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function all($page, $limit, $withSeller = false, $withCategory = false, $withPictures = false)
    {
        $sql = "SELECT * FROM `part` " .
            ($withCategory ? (" JOIN `category`" . ($withSeller ? ",`seller`" : "")) : ($withSeller ? "JOIN `seller` " : "")) .
            ($withSeller ? (" WHERE `part`.`seller_id`=`seller`.`seller_id` " . ($withCategory ? " AND `part`.`category_id` = `category`.`cat_id`" : ""))
                : ($withCategory ? " WHERE `part`.`category_id` = `category`.`cat_id`" : "")) .
            " ORDER BY `part`.`part_id` DESC  LIMIT ? OFFSET ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit, ($page - 1) * $limit]);
        $rows = $stmt->fetchAll();
        $parts = array();
        foreach ($rows as $row) {
            $part = self::fromRow($row);
            if ($withCategory) $part->category = Category::fromRow($row);
            if ($withSeller) $part->seller = Seller::fromRow($row);
            if ($withPictures) $part->pictures = PartPicture::byPart($part->id);
            array_push($parts, $part);
        }
        return $parts;
    }

    public static function nOfParts($query = '')
    {
        if ($query == '') {
            $sql = "SELECT COUNT(*) FROM `part` ";
        } else
            $sql = "SELECT COUNT(*) FROM `part` WHERE `pname` LIKE ? OR `description` LIKE ?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        if ($query == '') {
            $stmt->execute();
        } else {
            $query = "%$query%";
            $stmt->execute([$query, $query]);
        }

        return $stmt->fetchColumn(0);
    }

    public static function nOfPartsOfCategory($catId)
    {
        $count = 0;
        $categories = Category::byParent($catId);
        foreach ($categories as $category) {
            $count += self::nOfPartsOfCategory($category->getId());
        }
        $sql = "SELECT COUNT(*) FROM `part` WHERE `category_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$catId]);
        return $stmt->fetchColumn(0) + $count;
    }

    public static function nOfPartsOfSeller($sellerId)
    {

        $sql = "SELECT COUNT(*) FROM `part` WHERE `seller_id`=?";
        $pdo = DBConnection::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sellerId]);
        return $stmt->fetchColumn(0);
    }

}