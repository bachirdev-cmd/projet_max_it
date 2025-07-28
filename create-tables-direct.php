<?php
/**
 * Script direct pour créer les tables Railway
 */

echo "=== CRÉATION TABLES RAILWAY (DIRECT) ===\n";

// Credentials depuis votre render.yaml
$databaseUrl = 'postgresql://postgres:NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw@yamabiko.proxy.rlwy.net:16680/railway';

try {
    echo "🔗 Connexion à Railway...\n";
    
    // On va utiliser file_get_contents pour POST à votre API une fois déployée
    $setupUrl = 'https://projet-max-it.onrender.com/setup-database';
    
    echo "📡 Appel à $setupUrl\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30,
            'header' => 'User-Agent: Railway-Setup/1.0'
        ]
    ]);
    
    $response = file_get_contents($setupUrl, false, $context);
    
    if ($response) {
        $data = json_decode($response, true);
        if ($data['success']) {
            echo "✅ " . $data['message'] . "\n";
            if (isset($data['details'])) {
                echo "📊 Détails: " . print_r($data['details'], true) . "\n";
            }
        } else {
            echo "❌ Erreur: " . $data['message'] . "\n";
        }
    } else {
        echo "⚠️  Impossible de contacter l'API. L'app est-elle déployée ?\n";
        echo "💡 Déployez d'abord, puis réessayez.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Déployez votre code sur Render\n";
echo "2. Allez sur: https://projet-max-it.onrender.com/setup-database\n";
echo "3. Ou réexécutez ce script après déploiement\n";
