<?php

namespace Accolon\Route;

use Closure;

class Route
{
    private string $uri;
    private string $method;
    private Closure $action;
    private ?Middleware $middleware;
    private array $keys = [];

    public function __construct(string $method, string $uri, $action, ?Middleware $middleware, array $keys)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->middleware = $middleware;
        $this->keys = $keys;

        if (is_callable($action)) {
            $this->action = $action;
        }

        if (is_string($action)) {
            $string = explode(".", $action);

            $class = $this->controller . $string[0];

            $controller = new $class;

            $function = $string[1];

            $this->action = Closure::fromCallable([$controller, $function]);
        }
    }

    public static function create(string $method, string $uri, Closure $action, ?Middleware $middleware = null, array $keys = [])
    {
        return new Route($method, $uri, $action, $middleware, $keys);
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function getKey(int $i)
    {
        return $this->keys[$i];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }

    public function run(Request $request, Response $response)
    {
        if ($this->middleware) {
            return $this->middleware->handle($request, $response, $this->action);
        }
        
        return ($this->action)($request, $response);
    }
}