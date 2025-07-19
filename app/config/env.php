<?php

if (!isset($_ENV['APP_URL'])) {
    throw new \RuntimeException('Le fichier .env n\'est pas chargé correctement');
}

define('App_URL', $_ENV['APP_URL']);
define('host', $_ENV['DB_HOST']);
define('dbname', $_ENV['DB_NAME']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);



// # Configuration base de données
// App_URL=http://localhost:8000;
// DB_USERNAME=postgres
// DB_PASSWORD=776237675@BACHIR

// DSN=pgsql:host=localhost;port=5432;dbname=maxitteam

// # DSN=mysql:host=localhost;port=3306;dbname=maxitteam


