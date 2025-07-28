<?php
/**
 * Script d'initialisation de la base Railway pour MaxIT
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== INITIALISATION BASE RAILWAY ===\n";

try {
    // Variables Railway
    $databaseUrl = 'postgresql://postgres:NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw@yamabiko.proxy.rlwy.net:16680/railway';
    
    echo "Connexion à la base Railway...\n";
    echo "Host: yamabiko.proxy.rlwy.net:16680\n";
    
    // Connexion à la base
    $pdo = new PDO($databaseUrl);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connexion réussie !\n";

    // Exécution des migrations
    echo "\nExécution du script de migration...\n";
    
    $migrationFile = __DIR__ . '/migrations/script.sql';
    if (file_exists($migrationFile)) {
        $sql = file_get_contents($migrationFile);
        
        // Exécuter les requêtes une par une (au cas où il y aurait des erreurs)
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    echo "✓ Requête exécutée avec succès\n";
                } catch (PDOException $e) {
                    echo "⚠ Requête ignorée (probablement déjà existante): " . substr($statement, 0, 50) . "...\n";
                }
            }
        }
        
        echo "✓ Migrations terminées\n";
    } else {
        echo "⚠ Fichier de migration non trouvé: $migrationFile\n";
    }

    // Vérification des tables
    echo "\nVérification des tables créées:\n";
    $tables = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "✓ Table '$table': $count enregistrements\n";
    }

    // Ajout de données de test (optionnel)
    echo "\nVoulez-vous ajouter des données de test ? (y/N): ";
    $handle = fopen("php://stdin", "r");
    $response = trim(fgets($handle));
    fclose($handle);

    if (strtolower($response) === 'y' || strtolower($response) === 'yes') {
        echo "Ajout de données de test...\n";
        
        // Utilisateur de test
        $testUser = [
            'nom' => 'TEST',
            'prenom' => 'Utilisateur',
            'login' => '771234567',
            'password' => 'test123',
            'numerocarteidentite' => '1234567890123',
            'adresse' => 'Adresse de test',
            'typeuser' => 'client'
        ];

        $stmt = $pdo->prepare("
            INSERT INTO users (nom, prenom, login, password, numerocarteidentite, adresse, typeuser) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON CONFLICT (login) DO NOTHING
        ");
        
        $stmt->execute(array_values($testUser));
        echo "✓ Utilisateur de test ajouté (login: 771234567, password: test123)\n";

        // Compte principal de test
        $userId = $pdo->query("SELECT id FROM users WHERE login = '771234567'")->fetchColumn();
        if ($userId) {
            $stmt = $pdo->prepare("
                INSERT INTO compte (numero, numerotel, solde, typecompte, userid) 
                VALUES (?, ?, ?, ?, ?)
                ON CONFLICT (numero) DO NOTHING
            ");
            
            $stmt->execute([
                'CPT-TEST-' . time(),
                '771234567',
                10000.00,
                'principal',
                $userId
            ]);
            echo "✓ Compte principal de test ajouté (solde: 10,000 FCFA)\n";
        }
    }

    echo "\n=== INITIALISATION TERMINÉE ===\n";
    echo "✅ Base de données Railway prête pour MaxIT !\n";
    echo "🔗 Vous pouvez maintenant déployer sur Render\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
