<?php

namespace Accolon\Route\Traits;

trait Routes
{
    private array $routes;

    public function addRoute(string $method, string $url, $action, $middleware)
    {
        $this->routes[$method][$url] = $action;

        if ($middleware) {
            $this->middlewares[$method][$url] = new $middleware;
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
