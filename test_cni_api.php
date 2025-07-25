<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/env.php';

use App\Service\CniApiService;

echo "=== TEST DE L'API CNI ===\n";

$cniService = CniApiService::getInstance();

// Testons avec un numéro CNI probable (basé sur l'exemple de l'API)
$testCnis = [
    'CNI9876543210', // Exemple de l'API
    '1234567890123', // Numéro générique
    '9876543210123'  // Autre test
];

foreach ($testCnis as $cni) {
    echo "\n--- Test avec CNI: $cni ---\n";
    
    $result = $cniService->verifyCni($cni);
    
    if ($result) {
        echo "✓ CNI trouvée !\n";
        echo "Nom: " . ($result['nom'] ?? 'N/A') . "\n";
        echo "Prénom: " . ($result['prenom'] ?? 'N/A') . "\n";
        echo "Date naissance: " . ($result['date_naissance'] ?? 'N/A') . "\n";
        echo "Lieu naissance: " . ($result['lieu_naissance'] ?? 'N/A') . "\n";
        echo "CNI Recto URL: " . ($result['cni_recto_url'] ?? 'N/A') . "\n";
        echo "CNI Verso URL: " . ($result['cni_verso_url'] ?? 'N/A') . "\n";
        break; // Arrêter au premier succès
    } else {
        echo "✗ CNI non trouvée\n";
    }
}

echo "\n=== FIN DU TEST ===\n";
