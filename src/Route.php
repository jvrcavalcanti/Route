<?php

namespace Accolon\Route;

use Accolon\Route\Request;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\ResolverRoute;
use Psr\Container\ContainerInterface;

class Route
{
    use Middlewares, ResolverRoute;

    private string $uri;
    private string $method;
    private $action;
    private array $keys = [];
    private ContainerInterface $container;

    public function __construct(string $method, string $uri, $action, array $keys, ContainerInterface $container)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->keys = $keys;
        $this->action = $action;
        $this->container = $container;
        $this->startMiddlewareStack();
    }

    public static function create(string $method, string $uri, $action, ContainerInterface $container, array $keys = [])
    {
        return new Route($method, $uri, $action, $keys, $container);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getKey(int $i)
    {
        return $this->keys[$i] ?? null;
    }

    public function __invoke(Request $request)
    {
        $action;
        
        if (is_callable($this->action) || is_array($this->action)) {
            $action = $this->action;
        }

        if (is_string($this->action)) {
            $string = explode("->", $this->action);

            $action = $string;
        }

        return $this->resolveRoute($action);
    }

    public function run(Request $request)
    {
        return $this->runMiddlewares($request);
    }
}
