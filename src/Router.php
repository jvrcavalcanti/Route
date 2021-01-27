<?php

namespace Accolon\Route;

use Accolon\Container\Container;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Routes;
use Closure;

class Router
{
    use Routes, Methods, Middlewares;

    private Closure $fallback;
    private string $prefix = '';
    private Container $container;

    public function __construct(?Container $container = null)
    {
        $this->container = $container ? $container : new Container();
        $this->fallback = fn() => response()->text("Not found", 404);
        $this->startMiddlewareStack();

        $this->container->singletons(Container::class, $this->container);
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function registerMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $name => $middleware) {
            $this->container->bind($name, $middleware);
        }
    }

    public function getUrl(): string
    {
        $uri = urldecode(parse_url($_GET['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (strpos($uri, "/public") !== false) {
            $uri = explode("/public", $uri)[1];
        }
        $uri = $uri == "" ? "/" : $uri;
        return $uri;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function __invoke(Request $request)
    {
        $uri = $this->getUrl();
        $method = $this->getMethod();

        $route = null;

        if (!isset($this->routes[$method])) {
            $fallback = $this->fallback;
            return $fallback();
        }

        foreach ($this->routes[$method] as $routeM) {
            /** @var \Accolon\Route\Route $routeM */

            $patternUri = $routeM->uri;

            if (preg_match_all($patternUri, $uri, $keys, PREG_SET_ORDER)) {
                unset($keys[0][0]);
                $keys = $keys[0];

                $cont = 0;
                foreach ($keys as $key) {
                    $_REQUEST[$routeM->getKey($cont)] = $key;
                    $cont ++;
                }

                $route = $routeM;
                break;
            }
        }

        if (!$route) {
            $fallback = $this->fallback;
            return $fallback();
        }

        /** @var \Accolon\Route\Route $route */

        return $route->run($request);
    }

    public function dispatch()
    {
        $response = $this->runMiddlewares(request());

        if ($response instanceof Response) {
            echo $response->run();
        }

        if (!is_array($response) && !is_object($response)) {
            echo $response;
        }
    }
}
