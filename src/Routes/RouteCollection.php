<?php

namespace Accolon\Route\Routes;

use Accolon\Container\Container;
use Accolon\Route\Enums\Method;
use Accolon\Route\Exceptions\NotFoundException;
use Accolon\Route\Route;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Prefix;
use Accolon\Route\Utils\MatchList;
use Psr\Container\ContainerInterface;

class RouteCollection implements RouteCollectionInterface
{
    use Middlewares;

    private array $routes;
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
            Method::GET => new MatchList(),
            Method::POST => new MatchList(),
            Method::PUT => new MatchList(),
            Method::PATCH => new MatchList(),
            Method::DELETE => new MatchList(),
            Method::OPTIONS => new MatchList(),
            Method::HEAD => new MatchList()
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

    public function findRoute(string $method, string $uri): Route
    {
        foreach ($this->routes[$method] as $route) {
            if (preg_match($route->uri, $uri)) {
                return $route;
            }
        }

        throw new NotFoundException('Not found');
    }

    private function addRoute(string $method, string $uri, \Closure|string|callable $action): Route
    {
        if ($uri === '/' && $this->prefix === '') {
            $this->routes[$method]['\/'] = Route::create(
                $method,
                '/',
                $action,
                $this->container
            );
        }

        if ($uri === '/' && $this->prefix !== '') {
            $uri = $this->prefix;
        } elseif ($uri !== '/' && $this->prefix !== '') {
            $uri = $this->prefix . (str_contains($uri, '/') ? $uri : '/' . $uri);
        }

        $uri = preg_replace('#{.*}#', '.', $uri);

        return $this->routes[$method][$uri] = Route::create(
            $method,
            $uri,
            $action,
            $this->container
        );
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function merge(RouteCollection $routeCollection): void
    {
        foreach ($routeCollection->getRoutes() as $method => $routes) {
            $this->routes[$method] = array_merge($routes->toArray(), $this->routes[$method]->toArray());
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

    public function group(string $prefix = '', array $middlewares = [], ?\Closure $callback = null): void
    {
        if (is_null($callback)) {
            throw new \RuntimeException('\$callback is not must be null');
        }

        $collection = new RouteCollection('', $middlewares, $this->container);
        $collection->prefix($prefix);

        $callback($collection);

        $this->merge($collection);
    }
}
