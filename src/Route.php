<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Request;
use Accolon\Route\Traits\Methods;
use Accolon\Route\Traits\Middlewares;
use Accolon\Route\Traits\Routes;
use Closure;

class Route
{
    use Routes, Methods, Middlewares;

    private string $controller = "App\\Controller\\";
    private Closure $fallback;

    public function __construct()
    {
        $this->fallback = fn($response) => $response->text("Not found", 404);
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
    }

    public function getController()
    {
        return $this->controller;
    }

    public function defineController($controllerPath)
    {
        $this->controller = $controllerPath . "\\";
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

        $request = new Request;
        $response = new Response;

        $route = null;

        foreach($this->routes[$method] as $routeMethod) {
            $patternUri = $routeMethod["url"];

            if($url === "/") {
                break;
            }

            if(preg_match_all($patternUri, $url, $keys, PREG_SET_ORDER)) {
                unset($keys[0][0]);
                $keys = $keys[0];

                $cont = 0;
                foreach($keys as $key) {
                    $_REQUEST[$routeMethod["keys"][$cont]] = $key;
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

        if (is_callable($route["action"])) {
            $next = $route["action"];
        }

        if (is_string($route["action"])) {
            $action = explode(".", $route["action"]);

            $class = $this->controller . $action[0];

            $controller = new $class;

            $function = $action[1];

            $next = Closure::fromCallable([$controller, $function]);
        }

        foreach($this->globalMiddlewares ?? [] as $middleware) {
            $middleware = new $middleware;
            $result = $middleware->handle($request, $response);
            $request = $result[0];
            $response = $result[1];
        }

        $middleware = $route["middleware"];
        
        if($middleware) {
            echo $middleware->handle($request, $response, $next);
            exit;
        }

        echo ($next)($request, $response);
        exit;
    }
}
