<?php

namespace Accolon\Route\Traits;

trait Methods
{
    public function get(string $url, $action, $middleware = null)
    {
        self::addRoute("get", $url, $action, $middleware);
    }
    
    public function post(string $url, $action, $middleware = null)
    {
        self::addRoute("post", $url, $action, $middleware);
    }

    public function put(string $url, $action, $middleware = null)
    {
        self::addRoute("put", $url, $action, $middleware);
    }

    public function patch(string $url, $action, $middleware = null)
    {
        self::addRoute("patch", $url, $action, $middleware);
    }

    public function delete(string $url, $action, $middleware = null)
    {
        self::addRoute("delete", $url, $action, $middleware);
    }
}