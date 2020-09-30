<?php

namespace Accolon\Route;

use Accolon\Route\Request;
use Accolon\Route\Traits\Middlewares;
use Closure;

class Route
{
    use Middlewares;

    private string $uri;
    private string $method;
    private $action;
    private array $keys = [];

    public function __construct(string $method, string $uri, $action, array $keys)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->keys = $keys;
        $this->action = $action;
        $this->startMiddlewareStack();
    }

    public static function create(string $method, string $uri, $action, array $keys = [])
    {
        return new Route($method, $uri, $action, $keys);
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
        if (is_callable($this->action)) {
            return ($this->action)($request);
        }

        if (is_string($this->action)) {
            $string = explode("->", $action);

            return Closure::fromCallable([new $string[0], $string[1]])($request);
        }

        if (is_array($this->action)) {
            return Closure::fromCallable([$this->action[0], $this->action[1]])($request);
        }
    }

    public function run(Request $request)
    {
        return $this->runMiddlewares($request);
    }
}
