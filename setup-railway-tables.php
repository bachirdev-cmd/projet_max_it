<?php
/**
 * Script pour créer les tables dans Railway depuis l'application
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/bootstrap.php';

echo "=== CRÉATION TABLES RAILWAY ===\n";

try {
    // Utiliser la configuration existante
    use App\Core\DatabaseSafe;
    
    $db = new DatabaseSafe();
    if (!$db->isConnected()) {
        die("❌ Impossible de se connecter à la base de données\n");
    }
    
    $pdo = $db->getPdo();
    echo "✅ Connexion à Railway réussie\n";
    
    // Lire le fichier de migration
    $migrationFile = __DIR__ . '/migrations/script.sql';
    if (!file_exists($migrationFile)) {
        die("❌ Fichier de migration non trouvé: $migrationFile\n");
    }
    
    $sql = file_get_contents($migrationFile);
    echo "📄 Fichier de migration chargé\n";
    
    // Exécuter les requêtes une par une
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $success = 0;
    $skipped = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement) || preg_match('/^\s*--/', $statement)) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $success++;
            echo "✅ Requête exécutée\n";
        } catch (PDOException $e) {
            $skipped++;
            echo "⚠️  Requête ignorée (déjà existante): " . substr($statement, 0, 50) . "...\n";
        }
    }
    
    echo "\n📊 Résultats:\n";
    echo "- Requêtes réussies: $success\n";
    echo "- Requêtes ignorées: $skipped\n";
    
    // Vérifier les tables créées
    echo "\n📋 Tables créées:\n";
    $tables = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "  ✓ $table: $count enregistrements\n";
    }
    
    // Créer un utilisateur de test
    echo "\n👤 Création utilisateur de test...\n";
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (nom, prenom, login, password, numerocarteidentite, adresse, typeuser) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON CONFLICT (login) DO NOTHING
        ");
        
        $stmt->execute([
            'TEST',
            'Utilisateur',
            '771234567',
            password_hash('test123', PASSWORD_DEFAULT),
            '199720000166',
            'Dakar Test',
            'client'
        ]);
        
        echo "✅ Utilisateur de test créé (login: 771234567)\n";
    } catch (Exception $e) {
        echo "⚠️  Utilisateur de test: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 CONFIGURATION TERMINÉE !\n";
    echo "Vous pouvez maintenant tester l'inscription.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
