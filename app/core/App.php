<?php
namespace App\Core;
class App
{
    private static $dependencies = [
        
        "Router" => \App\Core\Router::class,
        "Database" => \App\Core\Database::class,
        "Validator" => \App\Core\Validator::class,
        "Session" => \App\Core\Session::class,
        "CompteController" => \App\Controller\CompteController::class,
        "SecurityController" => \App\Controller\SecurityController::class,
        "TransactionController" => \App\Controller\TransactionController::class,
        "UserService" => \App\Service\UserService::class,
        "SecurityService" => \App\Service\SecurityService::class,
        "UserRepository" => \App\Repository\UserRepository::class,
        "CompteRepository" => \App\Repository\CompteRepository::class,
        "CompteService" => \App\Service\CompteService::class,
        "TransactionService" => \App\Service\TransactionService::class,
        "TransactionRepository" => \App\Repository\TransactionRepository::class,


    ];

    public static function getDependency($key)
    {
       
        if(array_key_exists($key,self::$dependencies)){
           
            $class = self::$dependencies[$key];
            // var_dump($class);
            // die;
            if(class_exists($class) && method_exists($class, 'getInstance')){
                
                return $class::getInstance();
            }
            return new $class();
        }

    }
}
