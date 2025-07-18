<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Database;
use App\Core\App;
use App\Entity\Transaction;

use PDO;

class TransactionRepository extends AbstractRepository{

    private Database $database;
    private static ?TransactionRepository $instance = null;

    public function __construct(){
        $this->database = App::getDependency('Database');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getLast10Transactions($userId): array {
        $sql = "SELECT t.* 
                FROM transaction t
                INNER JOIN compte c ON t.compteid = c.id
                WHERE c.userid = :userid AND c.typecompte = 'principal'
                ORDER BY t.date DESC
                LIMIT 10";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->bindParam(':userid', $userId, PDO::PARAM_INT);
        $statement->execute();

        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            // On peut retourner le tableau ou instancier Transaction si besoin
            $transactions[] = $row;
        }
        return $transactions;
    }
    public function getAllTransactions(): array {
        $sql = "SELECT t.* 
                FROM transaction t
                INNER JOIN compte c ON t.compteid = c.id
                WHERE c.typecompte = 'principal'
                ORDER BY t.date DESC";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->execute();

        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = $row;
        }
        return $transactions;
    }
}

