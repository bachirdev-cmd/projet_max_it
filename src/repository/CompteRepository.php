<?php
namespace App\Repository;


use App\Core\Abstract\AbstractRepository;
use App\Core\Database;
use App\Core\App;
use PDO;

class CompteRepository {
    private Database $database;
    private static ?CompteRepository $instance = null;
    
    public function __construct() {
        $this->database = App::getDependency('Database');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    } 

    public function getSoldeByUserId(int $userId) : ?array{

        $sql = "SELECT * FROM compte WHERE userid = :userId AND typecompte = 'principal'";
        $stmt = $this->database->getPdo()->prepare(query:$sql);
        $stmt -> execute(params:['userId'=>$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
        return $result;
    }
}