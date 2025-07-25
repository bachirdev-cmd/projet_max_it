<?php
namespace App\Core;

class Helper {
    public static function clean($data) {
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }

    public static function generateCsrfToken() {
        $session = Session::getInstance();
        $token = $session->get('csrf_token');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            $session->set('csrf_token', $token);
        }
        return $token;
    }
}
