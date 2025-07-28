<?php
/**
 * Découverte des CNI disponibles dans l'API
 */

echo "=== DÉCOUVERTE CNI DISPONIBLES ===\n";

// CNI que nous connaissons déjà
$knownCnis = [
    '199720000166', // Testé et fonctionne
    '193729000167', // Testé et fonctionne  
    'CNI9876543210' // Testé et fonctionne
];

// CNI potentiels à tester (basés sur les patterns)
$testCnis = [
    // Années 1990s
    '199012345678',
    '199112345678', 
    '199212345678',
    '199312345678',
    '199412345678',
    '199512345678',
    '199612345678',
    '199712345678',
    '199812345678',
    '199912345678',
    
    // Format CNI avec lettres
    'CNI1234567890',
    'CNI0987654321',
    'CNI1111111111',
    'CNI2222222222',
    
    // Autres patterns
    '1937200100168', // Fourni par l'utilisateur mais pas trouvé
    '123456789012',
    '987654321098'
];

function testCni($cni) {
    $url = "https://app-daff-project.onrender.com/citoyen/" . urlencode($cni);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_HTTPHEADER => ['Accept: application/json']
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['data']) && $data['data']) {
            return $data['data'];
        }
    }
    
    return false;
}

echo "\n=== CNI CONFIRMÉS ===\n";
foreach ($knownCnis as $cni) {
    $result = testCni($cni);
    if ($result) {
        echo "✅ $cni - {$result['nom']} {$result['prenom']} (né le {$result['date_naissance']})\n";
    } else {
        echo "❌ $cni - Non trouvé\n";
    }
    sleep(1); // Éviter de surcharger l'API
}

echo "\n=== RECHERCHE NOUVEAUX CNI ===\n";
$found = 0;
foreach ($testCnis as $cni) {
    if ($found >= 5) break; // Limiter à 5 nouvelles découvertes
    
    echo "Test $cni... ";
    $result = testCni($cni);
    if ($result) {
        echo "✅ TROUVÉ ! {$result['nom']} {$result['prenom']}\n";
        $found++;
    } else {
        echo "❌\n";
    }
    sleep(1);
}

echo "\n=== RÉCAPITULATIF ===\n";
echo "CNI valides pour vos tests :\n";
foreach ($knownCnis as $cni) {
    echo "- $cni\n";
}

echo "\n=== FIN ===\n";
