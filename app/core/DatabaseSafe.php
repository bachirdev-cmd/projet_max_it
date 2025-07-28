<?php
namespace App\Core;

use PDO;

class DatabaseSafe {
    private ?PDO $pdo = null;
    private bool $connected = false;

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
                return;
            }

            $this->pdo = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 5
                ]
            );
            $this->connected = true;
            error_log("Connexion base de données réussie");
        } catch (\Exception $e) {
            error_log("Connexion base de données échouée: " . $e->getMessage());
            $this->connected = false;
        }
    }

    public function getPdo(): ?PDO {
        return $this->connected ? $this->pdo : null;
    }

    public function isConnected(): bool {
        return $this->connected;
    }
}
