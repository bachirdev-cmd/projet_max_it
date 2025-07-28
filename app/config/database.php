<?php

/**
 * Configuration de base de données adaptée pour Render
 */

// Priorité à DATABASE_URL (format Render) si disponible
if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
    // Format Render : postgres://user:password@host:port/dbname
    $databaseUrl = $_ENV['DATABASE_URL'];
    
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
    // Format local (.env classique)
    if (!isset($_ENV['DSN'])) {
        $_ENV['DSN'] = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $_ENV['DB_HOST'] ?? 'localhost',
            $_ENV['DB_PORT'] ?? 5432,
            $_ENV['DB_NAME'] ?? 'maxitgroupe'
        );
    }
}
