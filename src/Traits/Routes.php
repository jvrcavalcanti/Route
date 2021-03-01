<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Route;

trait Routes
{
    private array $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "PATCH" => [],
        "DELETE" => [],
        "OPTIONS" => [],
        "HEAD" => []
    ];

    public function addRoute(string $method, string $uri, $action): Route
    {
        if ($uri === "/" && $this->prefix === '') {
            return $this->routes[$method][] = Route::create(
                $method,
                '/^\/$/',
                $action,
                $this->container
            );
        }

        preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $uri, $keys, PREG_SET_ORDER);

        $newKeys = [];

        foreach ($keys as $key) {
            unset($key[0]);
            $newKeys[] = $key[1];
        }

        $uri = preg_replace('~{([^}]*)}~', "([^/]+)", $uri);
        $newUri = str_replace("/", "\/", $this->prefix . ($uri === '/' ? '' : $uri));

        return $this->routes[$method][] = Route::create(
            $method,
            '/^' . $newUri . "(\/)?$/",
            $action,
            $this->container,
            $newKeys
        );
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
