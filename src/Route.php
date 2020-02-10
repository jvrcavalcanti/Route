<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Request;

class Route
{
    private static $routes = [
        "get" => [],
        "post" => [],
        "put" => [],
        "path" => [],
        "delete" => []
    ];
    private static $controller = "App\\Controller\\";
    private static $middleware = "Accolon\\Route\\Middleware";

    public function get(string $url, $action)
    {
        self::$routes["get"][$url] = $action;
    }
    
    public function post(string $url, $action)
    {
        self::$routes["post"][$url] = $action;
    }

    public function put(string $url, $action)
    {
        self::$routes["put"][$url] = $action;
    }

    public function patch(string $url, $action)
    {
        self::$routes["path"][$url] = $action;
    }

    public function delete(string $url, $action)
    {
        self::$routes["delete"][$url] = $action;
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
        self::$controller = $controllerPath;
    }

    public static function getUrl(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return isset($_GET['path']) ? $_GET['path'] : $uri;
    }

    public static function addMiddleware($namespace): void
    {
        self::$middleware = $namespace;
    }

    public static function dispath(): void
    {
        $url = self::getUrl();
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $response = new Response();
        
        if(!isset(self::$routes[$method][$url])) {
            echo $response->text("Page not found", 404);
            return;
        }

        $middleware = new self::$middleware;

        if(!$middleware->validate(new Request, new Response)) {
            echo $response->text("Access Invalid", 401);
            return;
        }

        $route = self::$routes[$method][$url];

        if(is_callable($route)) {
            $return =  $route(new Request, new Response) ?? "";

            if(!is_array($return) || !is_object($return)) {
                echo $return;
                return;
            }
            echo "";
            return;
        }

        if(is_string($route)) {
            $action = explode("@", $route);

            $class = self::$controller . $action[0];

            $controller = new $class;

            $function = $action[1];

            $return = $controller->$function(new Request, new Response) ?? "";

            if(!is_array($return) && !is_object($return)) {
                echo $return;
                return;
            }
            
            echo "";
            return;
        }
    }
}