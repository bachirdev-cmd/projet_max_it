<?php

namespace App\Config;

use App\Config\Middleware\Auth;


class Middleware{

    public static function getMiddlewares():array
    {
        return[
            'auth'=> new Auth(),
            'cryptPassword' => new CryptPassword(),
        ];
    }
}

