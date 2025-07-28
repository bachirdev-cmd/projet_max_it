<?php
/**
 * Test direct de l'endpoint de vérification CNI
 */

echo "=== TEST ENDPOINT VÉRIFICATION CNI ===\n";

// Données de test (comme dans le formulaire)
$testData = [
    'cni' => '199720000166',
    'login' => '775626363',
    'adresse' => 'Apix'
];

echo "Données à envoyer:\n";
echo "CNI: " . $testData['cni'] . "\n";
echo "Login: " . $testData['login'] . "\n";
echo "Adresse: " . $testData['adresse'] . "\n\n";

// URL de l'endpoint (local ou production)
$url = 'http://localhost:8000/verify-cni'; // Changez selon votre config
// $url = 'https://projet-max-it.onrender.com/verify-cni';

echo "URL cible: $url\n\n";

// Préparer la requête
$jsonData = json_encode($testData);
echo "JSON à envoyer: $jsonData\n\n";

// Faire l'appel cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ],
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "=== RÉSULTAT ===\n";
echo "Code HTTP: $httpCode\n";

if ($error) {
    echo "Erreur cURL: $error\n";
} else {
    echo "Réponse brute: $response\n";
    
    $responseData = json_decode($response, true);
    if ($responseData) {
        echo "Réponse décodée:\n";
        print_r($responseData);
    }
}

echo "\n=== FIN DU TEST ===\n";
