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

    private $controller = "App\\Controller\\";

    public function getController()
    {
        return $this->controller;
    }

    public function defineController($controllerPath)
    {
        $this->controller = $controllerPath . "\\";
    }

    public function getUrl(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $_GET['path'] ?? $uri;
    }

    public function getMethod(): string
    {
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function dispath(): void
    {
        $url = $this->getUrl();
        $method = $this->getMethod();
        $response = new Response();
        
        if(!isset($this->routes[$method][$url])) {
            die($response->text("Page not found", 404));
        }

        foreach($this->middleware as $middle) {
            if(!$middle->handle(new Request, new Response)) {
                die($response->text("Access Invalid", 401));
            }
        }

        foreach ($this->middlewareRoutes[$url] as $middle) {
            if(!$middle->handle(new Request, new Response)) {
                die($response->text("Access Invalid", 401));
            }
        }

        $route = $this->routes[$method][$url];

        if(is_callable($route)) {
            $body = $route(new Request, new Response) ?? "";
        }

        if(is_string($route)) {
            $action = explode(".", $route);

            $class = $this->controller . $action[0];

            $controller = new $class;

            $function = $action[1];

            $body = $controller->$function(new Request, new Response) ?? "";
        }

        if(!is_array($body) || !is_object($body)) {
            die($body);
        }
    }
}