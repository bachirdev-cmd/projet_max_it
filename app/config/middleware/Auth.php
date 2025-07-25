<?php

namespace App\Config\Middleware;

use App\Core\App;

class Auth {
    public function __invoke(): bool {
        $session = App::getDependency('Session');
        if (!$session->isset('user')) {
            header('Location: /');
            exit();
        }
        return true;
    }
}

