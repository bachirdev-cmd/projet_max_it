<?php
/**
 * Test simple de connexion Railway (sans PDO si pas disponible)
 */

echo "=== TEST CONNEXION RAILWAY ===\n";

// Variables Railway extraites
$host = 'yamabiko.proxy.rlwy.net';
$port = '16680';
$database = 'railway';
$username = 'postgres';
$password = 'NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw';

echo "Configuration Railway:\n";
echo "- Host: $host\n";
echo "- Port: $port\n";
echo "- Database: $database\n";
echo "- User: $username\n";
echo "- Password: " . str_repeat('*', strlen($password)) . "\n";

// Test avec cURL si disponible
$testUrl = "http://$host:$port";
echo "\nTest de connectivité réseau...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "⚠ Connectivité réseau: $error\n";
} else {
    echo "✓ Host accessible (code: $httpCode)\n";
}

// Génération du DSN
$dsn = "pgsql:host=$host;port=$port;dbname=$database";
echo "\nDSN généré: $dsn\n";

// Variables d'environnement pour Render
echo "\n=== VARIABLES POUR RENDER ===\n";
echo "DATABASE_URL=postgresql://$username:$password@$host:$port/$database\n";
echo "DB_HOST=$host\n";
echo "DB_PORT=$port\n";
echo "DB_NAME=$database\n";
echo "DB_USERNAME=$username\n";
echo "DB_PASSWORD=$password\n";

echo "\n✅ Configuration prête pour Render !\n";
