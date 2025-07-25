<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== TEST DIRECT BASE DE DONNÉES ===\n";

try {
    $pdo = new PDO(
        $_ENV['DSN'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    
    echo "✓ Connexion à la base de données OK\n";
    
    // Test de la requête directe
    $login = '776788909';
    $password = '0000000000';
    
    echo "Test avec login: '$login' et password: '$password'\n";
    
    $sql = "SELECT id, nom, prenom, login, password, typeuser FROM users WHERE login = ? AND password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$login, $password]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "✓ Utilisateur trouvé:\n";
        print_r($result);
    } else {
        echo "✗ Aucun utilisateur trouvé avec ces identifiants exacts\n";
        
        // Vérifier si l'utilisateur existe avec ce login
        echo "\n--- Vérification du login uniquement ---\n";
        $sql2 = "SELECT id, nom, prenom, login, password, typeuser FROM users WHERE login = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$login]);
        $user = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✓ Utilisateur existe avec login '$login':\n";
            echo "  - ID: {$user['id']}\n";
            echo "  - Nom: {$user['nom']} {$user['prenom']}\n";
            echo "  - Login: '{$user['login']}'\n";
            echo "  - Password en base: '{$user['password']}'\n";
            echo "  - Password testé: '$password'\n";
            echo "  - Longueur password base: " . strlen($user['password']) . "\n";
            echo "  - Longueur password testé: " . strlen($password) . "\n";
            echo "  - Comparaison stricte: " . ($user['password'] === $password ? 'IDENTIQUE' : 'DIFFÉRENT') . "\n";
        } else {
            echo "✗ Aucun utilisateur avec le login '$login'\n";
        }
        
        // Vérifier tous les utilisateurs
        echo "\n--- Liste de tous les utilisateurs ---\n";
        $sql3 = "SELECT id, nom, prenom, login, password FROM users";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute();
        $users = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $u) {
            echo "ID: {$u['id']} | Login: '{$u['login']}' | Password: '{$u['password']}'\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
}
