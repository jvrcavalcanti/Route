<?php

namespace Accolon\Route\Routes;

use Accolon\Container\Container;
use Accolon\Route\Enums\Method;
use Accolon\Route\Route;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Prefix;
use Psr\Container\ContainerInterface;

class RouteCollection implements RouteCollectionInterface
{
    use Middlewares;

    private array $routes = [];
    private ContainerInterface $container;

    public function __construct(
        private string $prefix = '',
        private array $middlewares = [],
        ?ContainerInterface $container = null
    ) {
        $this->container = $container ?: new Container();
        $this->initRoutes();
    }

    protected function initRoutes()
    {
        $this->routes = [
            Method::GET => [],
            Method::POST => [],
            Method::PUT => [],
            Method::PATCH => [],
            Method::DELETE => [],
            Method::OPTIONS => [],
            Method::HEAD => []
        ];
    }

    public function prefix(string $prefix): self
    {
        if (!str_starts_with($prefix, '/')) {
            $prefix = '/' . $prefix;
        }

        $this->prefix = $prefix;
        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    protected function splitUri(string $uri): array
    {
        $uris = explode('/', $uri);
        if ($uris[0] === '') {
            $uris = array_splice($uris, 1);
        }
        $uris = array_filter($uris, fn($uri) => $uri !== '/');
        $uris = array_map(fn($uri) => $uri === '' ? '\/' : $uri, $uris);
        $uris = array_map(fn($uri) => preg_replace('#\{[a-z]{1,}\}#', '.*', $uri), $uris);
        return $uris;
    }

    private function &getPointer(string $method, string $uri)
    {
        $uris = $this->splitUri($uri);
        $pointer = &$this->routes[$method];

        foreach ($uris as $key => $uri) {
            if (!isset($pointer[$uri]) && $key < count($uris) - 1) {
                $pointer[$uri] = [];
            }

            $pointer = &$pointer[$uri];
        }

        return $pointer;
    }

    private function addRoute(string $method, string $uri, \Closure|string|callable $action): Route
    {
        if ($uri === '/' && $this->getPrefix() === '') {
            return $this->routes[$method]['\/'] = Route::create(
                $method,
                '/^\/$/',
                $action,
                $this->container
            );
        }

        $uri = $this->prefix . (str_contains($uri, '/') ? $uri : '/' . $uri);

        $pointer = &$this->getPointer($method, $uri);

        $pointer = Route::create(
            $method,
            $uri,
            $action,
            $this->container
        );

        return $pointer;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function merge(RouteCollection $routeCollection): void
    {
        foreach ($routeCollection->getRoutes() as $method => $routes) {
            $this->routes[$method] = array_merge($routes, $this->routes[$method]);
        }
    }

    public function get(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::GET, $uri, $action);
    }

    public function post(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::POST, $uri, $action);
    }

    public function put(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::PUT, $uri, $action);
    }

    public function patch(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::PATCH, $uri, $action);
    }

    public function delete(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::DELETE, $uri, $action);
    }

    public function options(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::OPTIONS, $uri, $action);
    }

    public function head(string $uri, \Closure|string|callable $action): Route
    {
        return $this->addRoute(Method::HEAD, $uri, $action);
    }

    public function group(string $prefix = '', array $middlewares = [], ?\Closure $callback = null)
    {
        if (is_null($callback)) {
            throw new \RuntimeException('\$callback is not must be null');
        }

        $collection = new RouteCollection($prefix, $middlewares, $this->container);

        $callback($collection);

        $this->merge($collection);
    }
}
