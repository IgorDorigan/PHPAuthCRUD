<?php

namespace App\database;

use PDO;
use PDOException;

class Database
{
    private static $instance;

    public static function getConnection()
    {
        if (!self::$instance) {
            try {
                $host = getenv('DB_HOST');
                $db   = getenv('DB_NAME');
                $user = getenv('DB_USER');
                $pass = getenv('DB_PASS');
                $charset = getenv('DB_CHARSET') ?: 'utf8';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

                self::$instance = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

