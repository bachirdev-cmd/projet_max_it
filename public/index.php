<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!class_exists(\Symfony\Component\Yaml\Yaml::class)) {
    die('symfony/yaml n\'est pas chargé !');
}

// Redirection automatique vers /login si on arrive sur la racine sans route
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($uri === '/' || $uri === '') {
    header('Location: /login');
    exit();
}

require_once __DIR__ . '/../app/config/bootstrap.php';



