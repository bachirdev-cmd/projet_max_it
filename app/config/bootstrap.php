<?php
// Start output buffering and session early
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../vendor/autoload.php';

// Chargement optionnel du .env (uniquement en local)
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/database.php';

use App\Core\Router;

$routes = require_once __DIR__ . '/../../routes/route.web.php';
Router::resolver($routes);
// Router::resolver($routes);
