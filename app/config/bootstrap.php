<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/database.php';

use App\Core\Router;

$routes = require_once __DIR__ . '/../../routes/route.web.php';
Router::resolver($routes);
// Router::resolver($routes);
