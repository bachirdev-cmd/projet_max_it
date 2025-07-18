<?php

namespace App\Config\Middleware;


class Auth{

    public function __invoke():bool{
        $session = App::getDependancy('Session');

        if (!$session->isset('user')) {
            header('Location:/');
            exit();
        }return true;

    }
}