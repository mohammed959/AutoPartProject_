<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 02:43 م
 */

class DBConnection
{

    private static $instance;

    public static function getPDO()
    {
        if (!isset(self::$instance)) {
            $db = 'AutoParts';
            $username = 'root';
            $password = '';
            $host = 'localhost';

            self::$instance = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Remove it latter
            self::$instance->query("SET foreign_key_checks = 0;");
        }
        return self::$instance;
    }

}



