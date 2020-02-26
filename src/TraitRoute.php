<?php

namespace Accolon\Route;

trait TraitRoute
{
    private static $routes = [];
    private static $controller = "App\\Controller\\";
    private static $middleware = [];
    private static $middlewareRoutes = [];
    private static $data;
    
    public static function addRoute(string $method, string $url, $action, $middleware)
    {
        if (!isset(self::$routes[$method])) {
            self::$routes = [];
        }

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

    public function getController()
    {
        return self::$controller;
    }

    public function defineController($controllerPath)
    {
        self::$controller = $controllerPath . "\\";
    }

    public static function addMiddleware($middlewares): void
    {
        self::$middleware[] = new $middlewares;
    }
}