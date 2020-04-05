<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Routes;
use Closure;

class Route
{
    use Routes, Methods, Middlewares;

    private string $controller = "App\\Controller\\";
    private Closure $fallback;

    public function __construct()
    {
        $this->fallback = fn($response) => $response->text("Not found", 404);
    }

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
        $uri = explode("/public", $uri)[1];
        $uri = $uri == "" ? "/" : $uri;
        return $_GET['path'] ?? $uri;
    }

    public function getMethod(): string
    {
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function dispatch(): void
    {
        $url = $this->getUrl();
        $method = $this->getMethod();

        $request = new Request;
        $response = new Response;
        
        if (!isset($this->routes[$method][$url])) {
            $fallback = $this->fallback;
            die($fallback($response));
        }

        $route = $this->routes[$method][$url];

        if (is_callable($route)) {
            $next = $route;
        }

        if (is_string($route)) {
            $action = explode(".", $route);

            $class = $this->controller . $action[0];

            $controller = new $class;

            $function = $action[1];

            $next = Closure::fromCallable([$controller, $function]);
        }

        foreach($this->globalMiddlewares ?? [] as $middleware) {
            $middleware = new $middleware;
            $result = $middleware->handle($request, $response);
            $request = $result[0];
            $response = $result[1];
        }

        $middleware = $this->middlewares[$method][$url] ?? null;
        
        if($middleware) {
            die((string) $middleware->handle($request, $response, $next));
        }

        die((string) $next($request, $response));
    }
}
