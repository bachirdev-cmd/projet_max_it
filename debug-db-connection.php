<?php
/**
 * Script de debug pour identifier le problème railway_
 */

echo "=== DEBUG CONNEXION DATABASE ===\n";

// Simuler l'environnement
$_ENV['DATABASE_URL'] = 'postgresql://postgres:NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw@yamabiko.proxy.rlwy.net:16680/railway';
$_ENV['APP_ENV'] = 'production';

echo "1. DATABASE_URL: " . $_ENV['DATABASE_URL'] . "\n";

// Test du parsing
$databaseUrl = $_ENV['DATABASE_URL'];
$parsed = parse_url($databaseUrl);

echo "2. Parse URL result:\n";
var_dump($parsed);

$dbName = ltrim($parsed['path'], '/');
echo "3. DB Name après ltrim: '$dbName'\n";

// Test de toutes les variables d'environnement qui pourraient interférer
echo "\n4. Variables d'environnement potentiellement problématiques:\n";
foreach ($_ENV as $key => $value) {
    if (stripos($key, 'DB') !== false || stripos($key, 'DATABASE') !== false) {
        echo "  $key = $value\n";
    }
}

// Reconstruction du DSN
$dsn = sprintf(
    'pgsql:host=%s;port=%d;dbname=%s',
    $parsed['host'],
    $parsed['port'] ?? 5432,
    $dbName
);

echo "\n5. DSN généré: $dsn\n";

// Test de connexion
echo "\n6. Test de connexion...\n";
try {
    $pdo = new PDO($dsn, $parsed['user'], $parsed['pass']);
    echo "✅ Connexion réussie!\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    
    // Analyser l'erreur
    if (strpos($e->getMessage(), 'railway_') !== false) {
        echo "🔍 PROBLÈME TROUVÉ: Un underscore est ajouté quelque part!\n";
        
        // Tester avec différentes variations
        $testNames = ['railway', 'railway_', trim($dbName), trim($dbName, '_')];
        foreach ($testNames as $testName) {
            $testDsn = "pgsql:host={$parsed['host']};port={$parsed['port']};dbname=$testName";
            echo "   Test avec '$testName': ";
            try {
                $testPdo = new PDO($testDsn, $parsed['user'], $parsed['pass']);
                echo "✅ SUCCÈS!\n";
                echo "   SOLUTION: utiliser '$testName'\n";
                break;
            } catch (Exception $te) {
                echo "❌\n";
            }
        }
    }
}

echo "\n=== FIN DEBUG ===\n";
