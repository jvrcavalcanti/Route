<?php

namespace Accolon\Route;

use Accolon\Container\Container;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Responses\Response;
use Accolon\Route\Routes\RouteCollection;
use Accolon\Route\Routes\RouteCollectionInterface;
use Accolon\Route\Traits\Middlewares;

class Router implements RouteCollectionInterface
{
    use Middlewares;

    private Container $container;
    private Dispatcher $dispatcher;
    private RouteCollection $collection;

    public function __construct(?Container $container = null)
    {
        $this->startMiddlewareStack();
        $this->collection = new RouteCollection();
        $this->dispatcher = new Dispatcher($this->collection);
        $this->container = $container ?? container();
    }

    public function get(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->get($uri, $action);
    }

    public function post(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->post($uri, $action);
    }

    public function put(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->put($uri, $action);
    }

    public function patch(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->patch($uri, $action);
    }

    public function delete(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->delete($uri, $action);
    }

    public function options(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->options($uri, $action);
    }

    public function head(string $uri, \Closure|string|callable $action): Route
    {
        return $this->collection->head($uri, $action);
    }

    public function group(string $prefix = '', array $middlewares = [], ?\Closure $callback = null): void
    {
        $this->collection->group(prefix: $prefix, middlewares: $middlewares, callback: $callback);
    }

    public function parseKeys(Route $route, Request $request)
    {
        preg_match_all($route->uri, $request->uri(), $keys, PREG_SET_ORDER);

        $keys = $keys[0];

        $keys = array_splice($keys, 1);

        foreach ($route->getKeysNames() as $i => $name) {
            $_REQUEST[$name] = $keys[$i];
        }
    }

    public function dispatch()
    {
        $request = $this->container->make(Request::class);

        $response = $this->runMiddlewares($request);
        return $response->body();

        try {
            $response = $this->runMiddlewares($request);
        } catch (BadRequestException $e) {
            $response = response()->{$e->getContentType()}($e->getMessage() ?? 'Bad Request', $e->getCode());
        } catch (InternalServerErrorException $e) {
            $response = ($this->fallback)($e->getMessage() ?? 'Internal Server Error');
        } catch (HttpException $e) {
            $response = response()->{$e->getContentType()}($e->getMessage(), $e->getCode());
        } finally {
            if ($response instanceof Response) {
                echo $response->body();
            }
    
            if (!is_array($response) && !is_object($response)) {
                echo $response;
            }
        }
    }

    public function __invoke(Request $request)
    {
        $route = $this->dispatcher->dispatch($request);
        $this->parseKeys($route, $request);
        $request->updateRequest();
        return $route->run($request);
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

                $route = $this->{$method}($uri, [$class, $function->getName()]);
                $route->middleware($attribute->middlewares);
            }
        }
    }
}
