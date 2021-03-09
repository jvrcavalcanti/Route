<?php

namespace Accolon\Route;

use Accolon\Container\Container;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Exceptions\ValidateFailException;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Routes;

class Router
{
    use Routes, Methods, Middlewares;

    protected bool $debug;
    protected \Closure $notFound;
    protected \Closure $fallback;
    protected string $prefix = '';
    protected Container $container;

    public function __construct(?Container $container = null, bool $debug = true)
    {
        $this->initRoutes();
        $this->debug = $debug;
        $this->container = $container ? $container : new Container();
        $this->notFound = fn() => abort("<center><h1>404 Not Found</h1><hr>Accolon Route PHP</center>", 404);
        $this->fallback = fn($message) => response()->html(
            "<center><h1>500 {$message}</h1><hr>Accolon Route PHP</center>",
            500
        );
        $this->startMiddlewareStack();

        $this->container->singletons(Container::class, $this->container);
    }

    public function prefix(string $prefix)
    {
        if ($prefix[0] != '/') {
            $prefix = '/' . $prefix;
        }

        $this->prefix = $prefix;
    }

    public function addPrefix(string $prefix)
    {
        if ($prefix[0] != '/') {
            $prefix = '/' . $prefix;
        }
        
        $this->prefix .= $prefix;
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
        return $uri === "" ? "/" : $uri;
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
            ($this->notFound)();
        }

        $route = $this->routes[$method][$uri];

        if (!$route) {
            ($this->notFound)();
        }

        /** @var \Accolon\Route\Route $route */

        preg_match_all($route->uri, $uri, $keys, PREG_SET_ORDER);

        unset($keys[0][0]);
        $keys = $keys[0];

        $cont = 0;
        foreach ($keys as $key) {
            $_REQUEST[$route->getKey($cont)] = $key;
            $cont ++;
        }

        return $route->run($request);
    }

    public function dispatch()
    {
        try {
            $response = $this->runMiddlewares(request());
        } catch (HttpException $e) {
            $response = response()->{$e->getContentType()}($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            $response = ($this->fallback)($e->getMessage() ?? 'Internal Server Error');
        } finally {
            if ($response instanceof Response) {
                echo $response->run();
            }
    
            if (!is_array($response) && !is_object($response)) {
                echo $response;
            }
        }
    }
}
