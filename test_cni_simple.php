<?php

echo "=== TEST SIMPLE DE L'API CNI ===\n";

$testCnis = [
    'CNI9876543210', // Exemple de l'API
    '1234567890123', // Numéro générique
    '9876543210123'  // Autre test
];

foreach ($testCnis as $cni) {
    echo "\n--- Test avec CNI: $cni ---\n";
    
    $url = 'https://projet-app-daff.onrender.com/citoyen/' . urlencode($cni);
    echo "URL: $url\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: MaxIT-App/1.0'
            ],
            'timeout' => 10
        ]
    ]);

    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "✗ Erreur lors de l'appel API\n";
        continue;
    }

    // Vérifier le code de réponse
    $status = 0;
    if (isset($http_response_header) && count($http_response_header) > 0) {
        $status_line = $http_response_header[0];
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $status_line, $matches)) {
            $status = (int) $matches[1];
        }
        echo "Status HTTP: $status\n";
    }
    
    if ($status === 404) {
        echo "✗ CNI non trouvée (404)\n";
        continue;
    }
    
    if ($status !== 200) {
        echo "✗ Erreur HTTP: $status\n";
        continue;
    }

    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "✗ Erreur JSON: " . json_last_error_msg() . "\n";
        continue;
    }

    echo "✓ CNI trouvée !\n";
    echo "Données reçues:\n";
    print_r($data);
    break; // Arrêter au premier succès
}

echo "\n=== FIN DU TEST ===\n";
