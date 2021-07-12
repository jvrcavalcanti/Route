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
    private array $keys = [];

    protected array $patternMatchers = [
        "number"        => '([0-9]+)',
        "word"          => '([a-zA-Z]+)',
        "alph_dash"     => '([a-zA-Z0-9-_]+)',
        "slug"          => '([a-z0-9-]+)',
        "uuid"          => '([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}+)'
    ];

    public function __construct(
        private string $method,
        private $action,
        private ContainerInterface $container,
        string $uri
    ) {
        $this->parseKeys($uri);
        $this->setUri($uri);
        $this->startMiddlewareStack();
    }

    public function setUri(string $uri)
    {
        if ($uri === '/') {
            $this->uri = '/^\/$/';
        }

        $uri = '/^' . str_replace("/", "\/", $uri) . '(\/)?$/';

        $this->uri = $uri;
    }

    private function parseKeys(string $uri)
    {
        preg_match_all('#\{(([a-zA-Z_]*)(\:([\w\d]*))?)\}#x', $uri, $keys, PREG_SET_ORDER);

        $this->keys = array_map(function ($key) {
            $new = [];

            if (count($key) === 3) {
                $name = $key[1];
                $pattern = '([^/]+)';
            }

            if (count($key) === 5) {
                $name = $key[2];
                $pattern = $this->patternMatchers[$key[4]];
            }

            $new[$name] = $pattern;
            return $new;
        }, $keys);
    }

    public static function create(string $method, string $uri, $action, ContainerInterface $container)
    {
        return new Route($method, $action, $container, $uri);
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
