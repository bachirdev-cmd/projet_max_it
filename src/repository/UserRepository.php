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
        $sql = "SELECT id, nom, prenom, login, password, typeuser FROM users 
                WHERE login = :login AND password = :password";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute([
            'login' => $login,
            'password' => $password // Dans un vrai projet, utilisez password_hash() et password_verify()
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId(): int {
        return (int) $this->database->getPdo()->lastInsertId();
    }

    public function create(array $userData): bool {
        try {
            $sql = "INSERT INTO users (nom, prenom, login, password, numerocarteidentite, photorecto, photoverso, adresse, typeuser) 
                    VALUES (:nom, :prenom, :login, :password, :numerocarteidentite, :photorecto, :photoverso, :adresse, :typeuser)";
            
            $stmt = $this->database->getPdo()->prepare($sql);
            return $stmt->execute([
                'nom' => $userData['nom'],
                'prenom' => $userData['prenom'],
                'login' => $userData['login'],
                'password' => $userData['password'],
                'numerocarteidentite' => $userData['numerocarteidentite'],
                'photorecto' => $userData['photorecto'],
                'photoverso' => $userData['photoverso'],
                'adresse' => $userData['adresse'],
                'typeuser' => $userData['typeuser']
            ]);
        } catch (\PDOException $e) {
            if ($e->getCode() == '23505') { // Code PostgreSQL pour violation de contrainte unique
                throw new \Exception("Ce numéro de téléphone est déjà utilisé.");
            }
            throw $e;
        }
    }

    public function findByLogin(string $login): array|false {
        $sql = "SELECT id, nom, prenom, login, typeuser FROM users WHERE login = :login";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute(['login' => $login]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
