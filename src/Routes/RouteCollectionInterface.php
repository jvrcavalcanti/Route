<?php

namespace Accolon\Route\Routes;

use Accolon\Route\Route;

interface RouteCollectionInterface
{
    public function get(string $uri, \Closure|string|callable $action): Route;
    public function post(string $uri, \Closure|string|callable $action): Route;
    public function put(string $uri, \Closure|string|callable $action): Route;
    public function patch(string $uri, \Closure|string|callable $action): Route;
    public function delete(string $uri, \Closure|string|callable $action): Route;
    public function options(string $uri, \Closure|string|callable $action): Route;
    public function head(string $uri, \Closure|string|callable $action): Route;
    public function group(string $prefix = '', array $middlewares = []);
}
