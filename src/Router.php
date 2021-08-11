<?php

namespace Accolon\Route;

use Accolon\Container\Container;
use Accolon\Route\Routes\RouteCollection;
use Accolon\Route\Routes\RouteCollectionInterface;

class Router implements RouteCollectionInterface
{
    private Container $container;
    private Dispatcher $dispatcher;
    private RouteCollection $collection;

    public function __construct(?Container $container = null)
    {
        
        $this->collection = new RouteCollection();
        $this->dispatcher = new Dispatcher($this->collection);
        $this->container = $container ?? container();
    }

    public function get(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->get($uri, $action);
    }

    public function post(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->post($uri, $action);
    }

    public function put(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->put($uri, $action);
    }

    public function patch(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->patch($uri, $action);
    }

    public function delete(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->delete($uri, $action);
    }

    public function options(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->options($uri, $action);
    }

    public function head(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->head($uri, $action);
    }

    public function group(string $prefix = '', array $middlewares = [], ?\Closure $callback = null): void
    {
        $this->collection->group(prefix: $prefix, middlewares: $middlewares, callback: $callback);
    }

    public function dispatch()
    {
        $request = $this->container->make(Request::class);
        $response = $this->dispatcher->dispatch($request);
        dd($response);
    }
}
