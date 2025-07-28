<?php

/**
 * Configuration de base de données adaptée pour Render
 */

// Debug: Afficher les variables disponibles
error_log("Variables disponibles: " . print_r($_ENV, true));

// Priorité à DATABASE_URL (format Render) si disponible
$databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL') ?? null;

if ($databaseUrl && !empty($databaseUrl)) {
    // Format Render : postgres://user:password@host:port/dbname
    error_log("Utilisation de DATABASE_URL: $databaseUrl");
    
    // Parse l'URL pour extraire les composants
    $parsed = parse_url($databaseUrl);
    
    $_ENV['DB_HOST'] = $parsed['host'];
    $_ENV['DB_PORT'] = $parsed['port'] ?? 5432;
    $_ENV['DB_NAME'] = ltrim($parsed['path'], '/');
    $_ENV['DB_USERNAME'] = $parsed['user'];
    $_ENV['DB_PASSWORD'] = $parsed['pass'];
    
    // Reconstruction du DSN
    $_ENV['DSN'] = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s',
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_NAME']
    );
} else {
    // Variables individuelles (depuis render.yaml)
    $_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
    $_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 5432;
    $_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'maxitgroupe';
    $_ENV['DB_USERNAME'] = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'postgres';
    $_ENV['DB_PASSWORD'] = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
    
    // Construction du DSN
    $_ENV['DSN'] = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s',
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_NAME']
    );
}

error_log("Configuration finale - DSN: " . $_ENV['DSN']);
error_log("DB_USERNAME: " . $_ENV['DB_USERNAME']);
error_log("DB_HOST: " . $_ENV['DB_HOST']);
