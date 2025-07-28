<?php

namespace App\Core;

class Session
{
private static ?Session $session = null;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                session_start();
            } else {
                error_log('Session cannot be started - headers already sent');
            }
        }
    }

    public static function getInstance(): Session
    {
        if (self::$session === null) {
            self::$session = new Session();
        }
        return self::$session;
    }

    public function set(string $key, $data): void
    {
        $_SESSION[$key] = $data;
    }

    public function get(string $key):mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function unset(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function destroy(string $key): void
    {
        $this->unset($key);
    }
}