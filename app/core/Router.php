<?php
namespace App\Core;

use App\Core\App;

class Router
{
    private static array $routes = [];

    public static function resolver(array $routes): void 
    {
        self::$routes = $routes;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!array_key_exists($uri, self::$routes)) {
            header('Location: /erreur');
            exit();
        }

        $route = self::$routes[$uri];
        $controllerName = $route['controller'];
        $actionName = $route['action'];

        // Vérification du middleware
        if (isset($route['middleware'])) {
            try {
                $middlewares = \App\Config\Middleware::getMiddlewares();
                if (isset($middlewares[$route['middleware']])) {
                    $middlewareResult = $middlewares[$route['middleware']]();
                    if (!$middlewareResult) {
                        header('Location: /');
                        exit();
                    }
                }
            } catch (\Exception $e) {
                error_log("Middleware error: " . $e->getMessage());
                header('Location: /erreur');
                exit();
            }
        }

        try {
            if (!class_exists($controllerName)) {
                throw new \Exception("Controller not found: $controllerName");
            }

            $controller = new $controllerName();
            
            if (!method_exists($controller, $actionName)) {
                throw new \Exception("Action not found: $actionName");
            }

            $controller->$actionName();
        } catch (\Exception $e) {
            error_log("Router error: " . $e->getMessage());
            header('Location: /erreur');
            exit();
        }
    }
}