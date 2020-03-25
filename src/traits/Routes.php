<?php

namespace Accolon\Route\Traits;

trait Routes
{   
    private $routes = [];
    private $middleware = [];
    private $middlewareRoutes = [];

    public function addMiddleware($middlewares): void
    {
        $this->middleware[] = new $middlewares;
    }

    public function addRoute(string $method, string $url, $action, $middleware)
    {
        $this->routes[$method][$url] = $action;
        
        if(!isset($this->middlewareRoutes[$url])) {
            $this->middlewareRoutes[$url] = [];
        }

        if($middleware) {
            $this->middlewareRoutes[$url][] = new $middleware;
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}