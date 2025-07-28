<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!class_exists(\Symfony\Component\Yaml\Yaml::class)) {
    die('symfony/yaml n\'est pas chargé !');
}

// Redirection automatique supprimée - gérée par le router

require_once __DIR__ . '/../app/config/bootstrap.php';



