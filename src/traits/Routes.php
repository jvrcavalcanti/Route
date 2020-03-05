<?php

namespace Accolon\Route\Traits;

trait Routes
{   
    private static $routes = [];
    private static $middleware = [];
    private static $middlewareRoutes = [];

    public static function addMiddleware($middlewares): void
    {
        self::$middleware[] = new $middlewares;
    }

    public static function addRoute(string $method, string $url, $action, $middleware)
    {
        self::$routes[$method][$url] = $action;
        
        if(!isset(self::$middlewareRoutes[$url])) {
            self::$middlewareRoutes[$url] = [];
        }

        if($middleware) {
            self::$middlewareRoutes[$url][] = new $middleware;
        }
    }

    public function getRoutes(): array
    {
        return self::$routes;
    }
}