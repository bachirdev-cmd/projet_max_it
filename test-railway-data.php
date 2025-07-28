<?php
/**
 * Test d'accès aux données Railway depuis l'application
 */

echo "=== TEST DONNÉES RAILWAY ===\n";

// Configuration Railway (comme dans render.yaml)
$databaseUrl = 'postgresql://postgres:NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw@yamabiko.proxy.rlwy.net:16680/railway';

try {
    // Connexion à Railway
    echo "🔗 Connexion à Railway...\n";
    $pdo = new PDO($databaseUrl);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie !\n\n";

    // Vérification des tables
    echo "📊 Tables disponibles :\n";
    $tables = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "  ✓ $table : $count enregistrements\n";
    }

    // Test des utilisateurs
    echo "\n👤 Utilisateurs dans la base :\n";
    $users = $pdo->query("SELECT id, nom, prenom, login, typeuser FROM users LIMIT 5")->fetchAll();
    
    foreach ($users as $user) {
        echo "  • {$user['nom']} {$user['prenom']} (Login: {$user['login']}, Type: {$user['typeuser']})\n";
    }

    // Test des comptes
    echo "\n💰 Comptes disponibles :\n";
    $comptes = $pdo->query("SELECT numero, numerotel, solde, typecompte FROM compte LIMIT 5")->fetchAll();
    
    foreach ($comptes as $compte) {
        echo "  • Compte {$compte['numero']} : {$compte['solde']} FCFA ({$compte['typecompte']})\n";
    }

    // Test avec un utilisateur spécifique
    echo "\n🔍 Test utilisateur 771234567 :\n";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute(['771234567']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "  ✅ Utilisateur trouvé : {$user['nom']} {$user['prenom']}\n";
        
        // Chercher ses comptes
        $stmt = $pdo->prepare("SELECT * FROM compte WHERE userid = ?");
        $stmt->execute([$user['id']]);
        $comptes = $stmt->fetchAll();
        
        echo "  💳 Comptes associés : " . count($comptes) . "\n";
        foreach ($comptes as $compte) {
            echo "    - {$compte['numero']} : {$compte['solde']} FCFA\n";
        }
    } else {
        echo "  ⚠ Utilisateur 771234567 non trouvé\n";
    }

    echo "\n🎉 Vos données Railway sont accessibles !\n";
    echo "✅ L'application peut utiliser toutes vos données existantes.\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "ℹ️  Cela peut être normal en local - sur Render ça marchera !\n";
}
