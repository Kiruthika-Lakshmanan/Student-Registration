<?php
namespace Student\Config;

class Db {
    const DBHOST = 'localhost';
    const DBUSER = 'ss4u';
    const DBPASS = '123456';
    const DBNAME = 'student';
    private static $instance = null;

    private function __construct()
    {
        $connection = new \mysqli(static::DBHOST, static::DBUSER, static::DBPASS, static::DBNAME);
        if ($connection->connect_error) {
            die('Connection failed: ' . $connection->connect_error);
        }
        static::$instance = $connection;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            new self();
        }
        return static::$instance;
    }
}

//$db = new Db('localhost','ss4u','123456','student_table');
//Db::connect();

