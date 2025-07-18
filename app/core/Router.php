<?php
namespace App\Core;

use App\Core\App;

class Router
{
    public static function resolver(array $routes): void {
        $uri= $_SERVER['REQUEST_URI'];

        if (array_key_exists($uri,$routes)) {
            $controllerName=$routes[$uri]['controller'];
            $actionName=$routes[$uri]['action'];

            //C'est le middleware c'est qui permet d'imposer au client de ne pas accéder au page d'accueil sans se connecter et de ne pas permettre aux hackers d'envoyer des injections 
            if(isset($route['middleware'])){
                $middlewares = Middleware::getMiddlewares();
                if (isset($middlewares[$route['middleware']])) {
                    $middlewares[$route['middleware']]();
                }
            }

            $controller = new $controllerName();
            // var_dump($controller);
            // die;

            $controller->$actionName();


            
        }
    }
    
}