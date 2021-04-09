<?php

class Connection {

    static function getConnection() {
        try {
            $host = 'localhost';
            $db_name = 'api_dev_portfolio';
            $db_user = 'root';
            $db_password = 'root';
            $dsn = 'mysql:host=' . $host . ';dbname=' . $db_name . ';';
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            $connection = new PDO($dsn, $db_user, $db_password, $options);
            return $connection;
        }catch(PDOException $e) {
            echo 'Error durante la conexion: ' . $e->getMessage();
            die();
        }
    }

}