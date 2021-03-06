<?php

use Accolon\Container\Container;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Request;
use Accolon\Route\Response;
use Accolon\Route\ResponseFactory;
use Accolon\Route\Router;

if (!function_exists('response')) {
    function response(...$params): ResponseFactory|Response
    {
        $resp = new ResponseFactory();
        return !empty($params) ? $resp->text(...$params) : $resp;
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
        throw new HttpException($message, $code, $typeContent);
    }
}

if (!function_exists('abort_when')) {
    function abort_when(bool $check, $message, int $code = 400, string $typeContent = "html")
    {
        $check && abort($message, $code, $typeContent);
    }
}

if (!function_exists('router')) {
    function router(): ?Router
    {
        return $GLOBALS['router'] ?? null;
    }
}

if (!function_exists('container')) {
    function container(): Container
    {
        $app = router();

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
