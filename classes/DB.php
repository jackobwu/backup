<?php

//使用php自带的pdo进行数据库的操作，同时为了简便，把connect，prepare，execute，包括参数传入的params合并成一个function，便于后面的操作

class DB {
    private static function connect() {
        $pdo = new PDO('mysql:host=127.0.0.1; dbname=upeng; charset=utf8', 'root', 'root314151');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    public static function query($query, $params = array()) {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll();
            return $data;
        }
    }
}

?>