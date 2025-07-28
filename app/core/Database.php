<?php
namespace App\Core;

use PDO;



class Database {
    protected PDO $pdo;

    public function __construct()
    {
        $dsn = $_ENV['DSN'] ?? '';
        $username = $_ENV['DB_USERNAME'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        
        if (empty($dsn) || $dsn === 'pgsql:host=;port=0;dbname=') {
            throw new \Exception('Database configuration not available');
        }
        
        $this->pdo = new PDO(
            $dsn,
            $username,
            $password,
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
