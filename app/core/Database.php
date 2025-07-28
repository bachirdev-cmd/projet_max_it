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
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 10
            ]
        );
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}
