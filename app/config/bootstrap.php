<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/env.php';

use App\Core\Router;

require_once '../routes/route.web.php';
Router::resolver($routes);
