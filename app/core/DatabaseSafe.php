<?php
namespace App\Core;

use PDO;

class DatabaseSafe extends Database {
    private bool $connected = false;

    public function __construct()
    {
        try {
            // Vérifier si les variables sont disponibles
            $dsn = $_ENV['DSN'] ?? null;
            $username = $_ENV['DB_USERNAME'] ?? null;
            $password = $_ENV['DB_PASSWORD'] ?? null;
            
            error_log("DatabaseSafe - DSN: " . ($dsn ?? 'NULL'));
            error_log("DatabaseSafe - USERNAME: " . ($username ?? 'NULL'));
            error_log("DatabaseSafe - DATABASE_URL: " . ($_ENV['DATABASE_URL'] ?? 'NULL'));

            if (!$dsn || empty($dsn) || $dsn === 'pgsql:host=;port=0;dbname=') {
                error_log("Base de données désactivée - variables d'environnement manquantes");
                $this->connected = false;
                // Créer un PDO mock pour éviter les erreurs
                $this->createMockPdo();
                return;
            }

            // Appeler le constructeur parent
            parent::__construct();
            $this->connected = true;
            error_log("Connexion base de données réussie");
        } catch (\Exception $e) {
            error_log("Connexion base de données échouée: " . $e->getMessage());
            $this->connected = false;
            // Mode fallback temporaire - permettre à l'app de fonctionner
            error_log("Mode fallback activé - utilisation SQLite temporaire");
            $this->createMockPdo();
        }
    }

    private function createMockPdo() {
        // Créer un PDO SQLite en mémoire comme fallback
        try {
            $this->pdo = new PDO('sqlite::memory:');
            $this->createSqliteTables();
            error_log("Tables SQLite créées avec succès");
        } catch (\Exception $e) {
            error_log("Impossible de créer le PDO mock: " . $e->getMessage());
        }
    }
    
    private function createSqliteTables() {
        // Créer les tables SQLite basiques
        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL,
                prenom TEXT NOT NULL,
                login TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                numerocarteidentite TEXT UNIQUE,
                photorecto TEXT,
                photoverso TEXT,
                adresse TEXT,
                typeuser TEXT NOT NULL CHECK (typeuser IN ('client', 'service_commercial'))
            )",
            "CREATE TABLE IF NOT EXISTS compte (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                numero TEXT,
                datecreation DATETIME DEFAULT CURRENT_TIMESTAMP,
                solde DECIMAL(15, 2) DEFAULT 0.00,
                numerotel TEXT NOT NULL,
                typecompte TEXT NOT NULL CHECK (typecompte IN ('principal', 'secondaire')),
                userid INTEGER NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(id)
            )",
            "CREATE TABLE IF NOT EXISTS transaction (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                date DATETIME DEFAULT CURRENT_TIMESTAMP,
                typetransaction TEXT NOT NULL CHECK (typetransaction IN ('depot', 'retrait', 'paiement')),
                montant DECIMAL(15, 2) NOT NULL,
                compteid INTEGER NOT NULL,
                FOREIGN KEY (compteid) REFERENCES compte(id)
            )"
        ];
        
        foreach ($tables as $sql) {
            $this->pdo->exec($sql);
        }
    }

    public function isConnected(): bool {
        return $this->connected;
    }
}
