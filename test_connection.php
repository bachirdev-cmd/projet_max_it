<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/bootstrap.php';

use App\Core\App;

echo "=== TEST DE CONNEXION ===\n";

// Test direct de la base de données
try {
    $database = App::getDependency('Database');
    $pdo = $database->getPdo();
    
    echo "✓ Connexion à la base de données OK\n";
    
    // Test de la requête directe
    $login = '776788909';
    $password = '0000000000';
    
    echo "Test avec login: $login et password: $password\n";
    
    $sql = "SELECT id, nom, prenom, login, password, typeuser FROM users WHERE login = :login AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'login' => $login,
        'password' => $password
    ]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "✓ Utilisateur trouvé:\n";
        print_r($result);
    } else {
        echo "✗ Aucun utilisateur trouvé\n";
        
        // Vérifier si l'utilisateur existe
        $sql2 = "SELECT id, nom, prenom, login, password, typeuser FROM users WHERE login = :login";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(['login' => $login]);
        $user = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "Utilisateur existe avec login $login mais mot de passe différent:\n";
            echo "Password en base: '{$user['password']}'\n";
            echo "Password testé: '$password'\n";
            echo "Longueur password base: " . strlen($user['password']) . "\n";
            echo "Longueur password testé: " . strlen($password) . "\n";
        } else {
            echo "Aucun utilisateur avec ce login\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== TEST VALIDATION ===\n";

// Test de validation
use App\Core\Validator;

$validator = App::getDependency('Validator');
$loginData = [
    'login' => '776788909',
    'password' => '0000000000'
];

if ($validator->validateLogin($loginData)) {
    echo "✓ Validation OK\n";
} else {
    echo "✗ Validation échouée:\n";
    print_r($validator->getErrors());
}

echo "\n=== TEST SECURITY SERVICE ===\n";

try {
    $securityService = App::getDependency('SecurityService');
    $user = $securityService->login('776788909', '0000000000');
    
    if ($user) {
        echo "✓ SecurityService::login OK\n";
        print_r($user);
    } else {
        echo "✗ SecurityService::login retourne false\n";
    }
} catch (Exception $e) {
    echo "✗ Exception SecurityService: " . $e->getMessage() . "\n";
}
