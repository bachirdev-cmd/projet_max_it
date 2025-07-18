<?php
namespace App\Repository;

use App\Core\Database;
use App\Core\App;
use PDO;

class UserRepository {
    private Database $database;
    private static ?UserRepository $instance = null;

    public function __construct() {
        $this->database = App::getDependency('Database');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Selectloginandpassword($login, $password): array|false {
        $sql = "SELECT * FROM users WHERE login = :login AND password = :password";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute([
            'login' => $login,
            'password' => $password
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}










