<?php

echo "=== TEST DE VALIDATION ===\n";

$login = '776788909';
$pattern = '/^(77|78|70|76|75)[0-9]{7}$/';

echo "Login testé: '$login'\n";
echo "Pattern: '$pattern'\n";
echo "Longueur: " . strlen($login) . "\n";
echo "Validation: " . (preg_match($pattern, $login) ? '✓ VALIDE' : '✗ INVALIDE') . "\n";

// Testons avec d'autres numéros de la base
$numeros = ['771234567', '775626363', '783786641', '776237675', '776788909'];

echo "\n--- Test de tous les numéros ---\n";
foreach ($numeros as $num) {
    $valid = preg_match($pattern, $num);
    echo "Numéro: $num | Longueur: " . strlen($num) . " | " . ($valid ? '✓ VALIDE' : '✗ INVALIDE') . "\n";
}
