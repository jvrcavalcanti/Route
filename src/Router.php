<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Routes;
use Closure;

class Router
{
    use Routes, Methods, Middlewares;

    private static string $controllerPath = "App\\Controller\\";
    private Closure $fallback;

    public function __construct()
    {
        $this->fallback = fn($response) => $response->text("Not found", 404);
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
    }

    public static function setControllersPath(string $path)
    {
        self::$controllerPath = $path;
    }

    public static function getControllersPath()
    {
        return self::$controllerPath;
    }

    public function getUrl(): string
    {
        $uri = urldecode(parse_url($_GET['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if(strpos($uri, "/public") !== false) {
            $uri = explode("/public", $uri)[1]; 
        }
        $uri = $uri == "" ? "/" : $uri;
        return $uri;
    }

    public function getMethod(): string
    {
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function cors(string $origin = "*",array $methods = ["GET", "POST", "DELETE", "PUT", "PATCH", "OPTIONS"])
    {
        $action = function(Request $req, Response $res) use ($methods, $origin) {
            $res->setHeader("Access-Control-Allow-Origin", "{$origin}");
            $res->setHeader("Access-Control-Allow-Methods", implode(",", $methods));
            $res->setHeader("Access-Control-Allow-Headers", "Content-Type, Authorization");
            $res->setHeader("Status", 200);
        };

        $this->options("/", $action);
        $this->options(".*", $action);
    }

    public function dispatch(): void
    {
        $url = $this->getUrl();
        $method = $this->getMethod();

        $response = new Response;

        $route = null;

        if (!isset($this->routes[$method])) {
            $fallback = $this->fallback;
            die($fallback($response));
        }

        foreach($this->routes[$method] as $routeMethod) {
            $patternUri = $routeMethod->getUri();

            /** @var \Accolon\Route\Route $routeMethod */

            if($url === "/") {
                break;
            }

            if(preg_match_all($patternUri, $url, $keys, PREG_SET_ORDER)) {
                unset($keys[0][0]);
                $keys = $keys[0];

                $cont = 0;
                foreach($keys as $key) {
                    $_REQUEST[$routeMethod->getKey($cont)] = $key;
                    $cont ++;
                }

                $route = $routeMethod;
                break;
            }
        }

        if ($url === "/") {
            $route = $this->routes[$method]["/"];
        }

        if (!$route) {
            $fallback = $this->fallback;
            die($fallback($response));
        }

        /** @var \Accolon\Route\Route $route */
        
        $request = new Request($_REQUEST);

        foreach($this->globalMiddlewares ?? [] as $middleware) {
            $middle = new $middleware;
            $result = $middle->handle($request, $response);
            $request = $result[0];
            $response = $result[1];
        }

        echo $route->run($request, $response);
    }
}
