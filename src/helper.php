<?php

use Accolon\Container\Container;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Request;
use Accolon\Route\ResponseFactory;
use Accolon\Route\Router;

if (!function_exists('response')) {
    function response(...$params)
    {
        if (!empty($params)) {
            return (new ResponseFactory)->text(...$params);
        }
        return new ResponseFactory();
    }
}

if (!function_exists('request')) {
    function request($param = null)
    {
        return is_null($param) ? new Request($_REQUEST) : request()->get($param);
    }
}

if (!function_exists('abort')) {
    function abort($message, int $code = 400, string $typeContent = "html")
    {
        throw new HttpException($code, $message, $typeContent);
    }
}

if (!function_exists('app')) {
    function app(): ?Router
    {
        return $GLOBALS['app'] ?? $GLOBALS['router'] ?? null;
    }
}

if (!function_exists('container')) {
    function container(): Container
    {
        $app = app();

        if (!$app) {
            return new Container;
        }

        return $app->getContainer();
    }
}

if (!function_exists('resolve')) {
    function resolve($id)
    {
        return container()->make($id);
    }
}
