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

    public function ajouterSecondaire(array $data): bool
    {
        try {
            error_log("Tentative d'ajout compte secondaire avec données: " . print_r($data, true));
            
            $sql = "INSERT INTO compte (numero, datecreation, solde, numerotel, typecompte, userid)
                VALUES (:numero, :datecreation, :solde, :numerotel, 'secondaire', :userid)";
            $stmt = $this->database->getPdo()->prepare($sql);

            $result = $stmt->execute([
                ':numero' => $data['numero'],
                ':datecreation' => $data['datecreation'],
                ':solde' => $data['solde'],
                ':numerotel' => $data['numerotel'],
                ':userid' => $data['userid']
            ]);

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erreur SQL: " . print_r($errorInfo, true));
            }

            return $result;
        } catch (\Exception $e) {
            error_log("Exception lors de l'ajout: " . $e->getMessage());
            return false;
        }
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSolde(int $compteId, float $nouveauSolde): void
    {
        $sql = "UPDATE compte SET solde = :solde WHERE id = :id";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute([
            ':solde' => $nouveauSolde,
            ':id' => $compteId
        ]);
    }
    public function findComptesSecondairesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId AND typecompte = 'secondaire'";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create(array $data): bool {
        try {
            $sql = "INSERT INTO compte (numero, datecreation, solde, numerotel, typecompte, userid)
                    VALUES (:numero, :datecreation, :solde, :numerotel, :typecompte, :userid)";
            
            $stmt = $this->database->getPdo()->prepare($sql);
            return $stmt->execute([
                ':numero' => $data['numero'],
                ':datecreation' => $data['datecreation'],
                ':solde' => $data['solde'] ?? 0,
                ':numerotel' => $data['numerotel'],
                ':typecompte' => $data['typecompte'],
                ':userid' => $data['userid']
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur SQL creation compte: " . $e->getMessage());
            return false;
        }
    }
}