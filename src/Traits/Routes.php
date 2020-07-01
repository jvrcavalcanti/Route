<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Routing\Route;

trait Routes
{
    private array $routes;

    public function addRoute(string $method, string $url, $action, $middleware): Route
    {
        if ($url === "/") {
            $this->routes[$method][$url] = Route::create(
                $this,
                $method,
                "/^\/$/",
                $action,
                $middleware ? new $middleware : null
            );
            return $this->routes[$method][$url];
        }

        preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $url, $keys, PREG_SET_ORDER);

        $newKeys = [];

        foreach($keys as $key) {
            unset($key[0]);
            $newKeys[] = $key[1];
        }

        $url = preg_replace('~{([^}]*)}~', "([^/]+)", $url);
        $newUrl = str_replace("/", "\/", $url);

        $this->routes[$method][$url] = Route::create(
            $this,
            $method,
            "/" . $newUrl . "$/",
            $action,
            $middleware ? new $middleware : null,
            $newKeys
        );

        return $this->routes[$method][$url];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
