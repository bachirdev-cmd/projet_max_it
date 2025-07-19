<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Database;

try {
    $db = new Database();
    $pdo = $db->getPdo();
    $sql = file_get_contents(__DIR__ . '/../migrations/script.sql');
    $pdo->exec($sql);
    echo "Migration effectuée avec succès!\n";
} catch (Exception $e) {
    echo "Erreur de migration : " . $e->getMessage() . "\n";
}
catch (Exception $e) {
    echo "Erreur de migration : " . $e->getMessage() . "\n";
}
