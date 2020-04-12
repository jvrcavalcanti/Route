<?php

namespace Accolon\Route\Traits;

trait Routes
{
    private array $routes;

    public function addRoute(string $method, string $url, $action, $middleware): void
    {
        if ($url === "/") {
            $this->routes[$method][$url] = [
                "url" => "/^\/$/",
                "method" => $method,
                "action" => $action,
                "middleware" => $middleware ? new $middleware : null
            ];
            return;
        }

        preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $url, $keys, PREG_SET_ORDER);

        $newKeys = [];

        foreach($keys as $key) {
            unset($key[0]);
            $newKeys[] = $key[1];
        }

        $url = preg_replace('~{([^}]*)}~', "([^/]+)", $url);
        $url = str_replace("/", "\/", $url);

        $this->routes[$method][$url] = [
            "url" => "/" . $url . "$/",
            "method" => $method,
            "action" => $action,
            "middleware" => $middleware ? new $middleware : null,
            "keys" => $newKeys
        ];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
