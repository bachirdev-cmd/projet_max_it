<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once __DIR__ . '/env.php';

use App\Core\Router;

require_once '../routes/route.web.php';
Router::resolver($routes);
Router::resolver($routes);
