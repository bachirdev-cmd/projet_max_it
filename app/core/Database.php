<?php
namespace App\Core;

use PDO;



class Database {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            $_ENV['DSN'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}
