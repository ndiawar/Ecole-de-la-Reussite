<?php
class Database {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $db = 'ecole-de-la-reussite';
        $user = 'root';
        $pass = '';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPDO() {
        return $this->pdo;
    }
}

