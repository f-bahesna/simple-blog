<?php

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if(self::$instance === null){
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $db = getenv('DB_NAME') ?: 'blog_db';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '43214321';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$instance = new PDO($dsn, $user, $pass, $options);

        }
        return self::$instance;
    }
}