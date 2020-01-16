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

    public function path(string $url, $action)
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

    public static function getUrl(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return isset($_GET['path']) ? $_GET['path'] : $uri;
    }

    public static function dispath(): string
    {
        $url = self::getUrl();
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        if(!isset(self::$routes[$method][$url])) {
            return Response::response()->text("Page not found", 404);
        }

        $route = self::$routes[$method][$url];

        if(is_callable($route)) {
            return $route(new Request);
        }
    }
}