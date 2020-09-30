<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Route;
use Closure;

trait Methods
{
    public function get(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("GET", $url, $action, $middleware);
    }
    
    public function post(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("POST", $url, $action, $middleware);
    }

    public function put(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("PUT", $url, $action, $middleware);
    }

    public function patch(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("PATCH", $url, $action, $middleware);
    }

    public function delete(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("DELETE", $url, $action, $middleware);
    }

    public function options(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("OPTIONS", $url, $action, $middleware);
    }
    
    public function head(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("HEAD", $url, $action, $middleware);
    }

    public function fallback(Closure $foo)
    {
        $this->fallback = $foo;
    }
}
