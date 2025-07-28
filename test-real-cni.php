<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/env.php';

use App\Service\CniApiService;

echo "=== TEST CNI RÉELLES ===\n";

$cniService = CniApiService::getInstance();

// CNI réelles fournies par l'utilisateur
$realCnis = [
    '199720000166',
    '1937200100168', 
    '193729000167'
];

foreach ($realCnis as $cni) {
    echo "\n--- Test avec CNI réelle: $cni ---\n";
    
    $result = $cniService->verifyCni($cni);
    
    if ($result) {
        echo "✅ CNI TROUVÉE !\n";
        echo "Nom: " . ($result['nom'] ?? 'N/A') . "\n";
        echo "Prénom: " . ($result['prenom'] ?? 'N/A') . "\n";
        echo "Date naissance: " . ($result['date_naissance'] ?? 'N/A') . "\n";
        echo "Lieu naissance: " . ($result['lieu_naissance'] ?? 'N/A') . "\n";
        echo "CNI Recto URL: " . ($result['cni_recto_url'] ?? 'N/A') . "\n";
        echo "CNI Verso URL: " . ($result['cni_verso_url'] ?? 'N/A') . "\n";
    } else {
        echo "❌ CNI non trouvée\n";
    }
}

echo "\n=== VALIDATION FORMAT ===\n";
foreach ($realCnis as $cni) {
    $valid = preg_match('/^\d{12,13}$/', $cni);
    echo "CNI $cni : " . ($valid ? "✅ Format valide" : "❌ Format invalide") . "\n";
}

echo "\n=== FIN DU TEST ===\n";
