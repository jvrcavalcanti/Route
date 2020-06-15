<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Routing\Route;
use Closure;

trait Methods
{
    public function get(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("get", $url, $action, $middleware);
    }
    
    public function post(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("post", $url, $action, $middleware);
    }

    public function put(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("put", $url, $action, $middleware);
    }

    public function patch(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("patch", $url, $action, $middleware);
    }

    public function delete(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("delete", $url, $action, $middleware);
    }

    public function options(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("options", $url, $action, $middleware);
    }
    
    public function head(string $url, $action, $middleware = null): Route
    {
        return $this->addRoute("head", $url, $action, $middleware);
    }

    public function fallback(Closure $foo)
    {
        $this->fallback = $foo;
    }
}