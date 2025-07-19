<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Correction du chemin vers .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Database;

try {
    $db = new Database();
    $pdo = $db->getPdo();

    // Désactiver temporairement les contraintes de clés étrangères
    $pdo->exec('SET session_replication_role = \'replica\';');

    // Vérifier si l'utilisateur existe déjà
    $loginTest = '771234567';
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login");
    $stmt->execute(['login' => $loginTest]);
    $userId = $stmt->fetch(\PDO::FETCH_COLUMN);

    if (!$userId) {
        // Utilisateur de test
        $sql = "INSERT INTO users (nom, prenom, login, password, typeuser) VALUES 
                ('Diallo', 'Amadou', :login, '1234', 'client')
                RETURNING id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['login' => $loginTest]);
        $userId = $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    // Compte principal
    $stmt = $pdo->prepare("SELECT id FROM compte WHERE userid = :userId AND typecompte = 'principal'");
    $stmt->execute(['userId' => $userId]);
    $compteId = $stmt->fetch(\PDO::FETCH_COLUMN);

    if (!$compteId) {
        $sql = "INSERT INTO compte (numero, datecreation, solde, numerotel, typecompte, userid) VALUES 
                ('CPT-PRIN-" . $userId . "', CURRENT_TIMESTAMP, 500000, :login, 'principal', :userId)
                RETURNING id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['login' => $loginTest, 'userId' => $userId]);
        $compteId = $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    // Transactions de test
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaction WHERE compteid = :compteId");
    $stmt->execute(['compteId' => $compteId]);
    $nbTransactions = $stmt->fetch(\PDO::FETCH_COLUMN);

    if ($nbTransactions == 0) {
        $transactions = [
            ['depot', 200000],
            ['retrait', 50000],
            ['depot', 100000],
            ['paiement', 25000],
            ['retrait', 75000]
        ];
        $sql = "INSERT INTO transaction (date, typetransaction, montant, compteid) 
                VALUES (CURRENT_TIMESTAMP - (random() * interval '10 days'), :type, :montant, :compteId)";
        $stmt = $pdo->prepare($sql);
        foreach ($transactions as [$type, $montant]) {
            $stmt->execute([
                'type' => $type,
                'montant' => $montant,
                'compteId' => $compteId
            ]);
        }
    }

    // Réactiver les contraintes
    $pdo->exec('SET session_replication_role = \'origin\';');

    echo "Données de test insérées avec succès!\n";
    echo "Login: 771234567\n";
    echo "Password: 1234\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}