<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Routes;

class Route
{
    use Routes;
    use Methods;

    private static $controller = "App\\Controller\\";

    public function getController()
    {
        return self::$controller;
    }

    public function defineController($controllerPath)
    {
        self::$controller = $controllerPath . "\\";
    }

    public static function getUrl(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $_GET['path'] ?? $uri;
    }

    public static function getMethod(): string
    {
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function dispath(): void
    {
        $url = self::getUrl();
        $method = self::getMethod();
        $response = new Response();
        
        if(!isset(self::$routes[$method][$url])) {
            die($response->text("Page not found", 404));
        }

        foreach(self::$middleware as $middle) {
            if(!$middle->handle(new Request, new Response)) {
                die($response->text("Access Invalid", 401));
            }
        }

        foreach (self::$middlewareRoutes[$url] as $middle) {
            if(!$middle->handle(new Request, new Response)) {
                die($response->text("Access Invalid", 401));
            }
        }

        $route = self::$routes[$method][$url];

        if(is_callable($route)) {
            $body = $route(new Request, new Response) ?? "";
        }

        if(is_string($route)) {
            $action = explode(".", $route);

            $class = self::$controller . $action[0];

            $controller = new $class;

            $function = $action[1];

            $body = $controller->$function(new Request, new Response) ?? "";
        }

        if(!is_array($body) || !is_object($body)) {
            die($body);
        }
    }
}