<?php
/**
 * Script de configuration pour la production sur Render
 */

require_once __DIR__ . '/vendor/autoload.php';

// Suppression des echo en production pour éviter les headers déjà envoyés
// echo "=== Configuration de la production ===\n";

try {
    // Chargement optionnel des variables d'environnement
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    // Configuration de la base de données
    require_once __DIR__ . '/app/config/database.php';
    
    // Test de connexion à la base de données (logs au lieu d'echo)
    error_log("Test de connexion à la base de données Railway...");
    error_log("Host: " . ($_ENV['DB_HOST'] ?? 'N/A'));
    error_log("Port: " . ($_ENV['DB_PORT'] ?? 'N/A'));
    error_log("Database: " . ($_ENV['DB_NAME'] ?? 'N/A'));
    error_log("DSN utilisé: " . $_ENV['DSN']);
    
    $pdo = new PDO(
        $_ENV['DSN'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10
        ]
    );

    error_log("✓ Connexion à la base de données réussie");

    // Exécution des migrations
    error_log("Exécution des migrations...");
    
    $migrationFile = __DIR__ . '/migrations/script.sql';
    if (file_exists($migrationFile)) {
        $sql = file_get_contents($migrationFile);
        $pdo->exec($sql);
        error_log("✓ Migrations exécutées");
    } else {
        error_log("⚠ Fichier de migration non trouvé");
    }

    // Création du dossier uploads
    $uploadDir = __DIR__ . '/public/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        error_log("✓ Dossier uploads créé");
    }

    $cniDir = $uploadDir . '/cni';
    if (!is_dir($cniDir)) {
        mkdir($cniDir, 0777, true);
        error_log("✓ Dossier uploads/cni créé");
    }

    error_log("=== Configuration terminée avec succès ===");

} catch (Exception $e) {
    error_log("✗ Erreur setup-production : " . $e->getMessage());
    exit(1);
}
