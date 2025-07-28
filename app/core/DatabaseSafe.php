<?php
namespace App\Core;

use PDO;

class DatabaseSafe extends Database {
    private bool $connected = false;
    protected PDO $pdo;

    public function __construct()
    {
        try {
            // Vérifier si les variables sont disponibles
            $dsn = $_ENV['DSN'] ?? null;
            $username = $_ENV['DB_USERNAME'] ?? null;
            $password = $_ENV['DB_PASSWORD'] ?? null;

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
            $this->createMockPdo();
        }
    }

    private function createMockPdo() {
        // Créer un PDO SQLite en mémoire comme fallback
        try {
            $this->pdo = new PDO('sqlite::memory:');
        } catch (\Exception $e) {
            error_log("Impossible de créer le PDO mock: " . $e->getMessage());
        }
    }

    public function isConnected(): bool {
        return $this->connected;
    }
}
