<?php

namespace Accolon\Route;

use Accolon\Container\Container;
use Accolon\Route\Attributes\Route;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Exceptions\ServerErrorException;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Prefix;
use Accolon\Route\Traits\Routes;
use Accolon\Route\Utils\StringStack;

class Router
{
    use Routes, Methods, Middlewares, Prefix;

    protected bool $debug;
    protected \Closure $notFound;
    protected \Closure $fallback;
    protected Container $container;

    public function __construct(?Container $container = null, bool $debug = true)
    {
        $this->initRoutes();
        $this->debug = $debug;
        $this->prefix = new StringStack;
        $this->container = $container ? $container : new Container();
        $this->notFound = fn() => abort("<center><h1>404 Not Found</h1><hr>Accolon Route PHP</center>", 404);
        $this->fallback = fn($message) => response()->html(
            "<center><h1>500 {$message}</h1><hr>Accolon Route PHP</center>",
            500
        );
        $this->startMiddlewareStack();

        $this->container->singletons(Container::class, $this->container);
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

    public function attributeRoutes(string $path, string $namespace = 'App\\Controllers')
    {
        $files = scandir($path);
        $files = array_splice($files, 2);
        $files = array_filter($files, fn($file) => str_ends_with($file, '.php'));

        $classes = [];

        foreach ($files as $file) {
            $tmp = $path . '/' . $file;

            if (is_dir($tmp)) {
                $this->attributeRoutes($tmp, $namespace . '\\' . $file);
                continue;
            }

            $classes[] = $namespace . '\\' . (explode('.', $file))[0];
        }

        foreach ($classes as $class) {
            $reflectionClass = new \ReflectionClass($class);
            $functions = $reflectionClass->getMethods();
            $functions = array_filter($functions, fn($function) => $function->getName() !== '__construct');
            
            foreach ($functions as $function) {
                $attributes = $function->getAttributes(Route::class);
                if (empty($attributes)) {
                    continue;
                }

                $attribute = ($attributes[0])->newInstance();

                $method = $attribute->method;
                $uri = $attribute->uri;

                $this->{$method}($uri, [$class, $function->getName()]);
            }
        }
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
        } catch (ServerErrorException $e) {
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
