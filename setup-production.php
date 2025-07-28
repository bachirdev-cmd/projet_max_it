<?php
/**
 * Script de configuration pour la production sur Render
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== Configuration de la production ===\n";

try {
    // Chargement des variables d'environnement
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    // Configuration de la base de données
    require_once __DIR__ . '/app/config/database.php';
    
    // Test de connexion à la base de données
    echo "Test de connexion à la base de données Railway...\n";
    echo "Host: " . ($_ENV['DB_HOST'] ?? 'N/A') . "\n";
    echo "Port: " . ($_ENV['DB_PORT'] ?? 'N/A') . "\n";
    echo "Database: " . ($_ENV['DB_NAME'] ?? 'N/A') . "\n";
    echo "DSN utilisé: " . $_ENV['DSN'] . "\n";
    
    $pdo = new PDO(
        $_ENV['DSN'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10
        ]
    );

    echo "✓ Connexion à la base de données réussie\n";

    // Exécution des migrations
    echo "Exécution des migrations...\n";
    
    $migrationFile = __DIR__ . '/migrations/script.sql';
    if (file_exists($migrationFile)) {
        $sql = file_get_contents($migrationFile);
        $pdo->exec($sql);
        echo "✓ Migrations exécutées\n";
    } else {
        echo "⚠ Fichier de migration non trouvé\n";
    }

    // Création du dossier uploads
    $uploadDir = __DIR__ . '/public/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo "✓ Dossier uploads créé\n";
    }

    $cniDir = $uploadDir . '/cni';
    if (!is_dir($cniDir)) {
        mkdir($cniDir, 0777, true);
        echo "✓ Dossier uploads/cni créé\n";
    }

    echo "=== Configuration terminée avec succès ===\n";

} catch (Exception $e) {
    echo "✗ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
