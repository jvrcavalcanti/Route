<?php

use Accolon\Route\Request;
use Accolon\Route\ResponseFactory;
use Accolon\Route\Router;

function response(...$params)
{
    if (!empty($params)) {
        return (new ResponseFactory)->text(...$params);
    }
    return new ResponseFactory();
}

function request($param = null)
{
    return is_null($param) ? new Request($_REQUEST) : request()->get($param);
}

function app(): Router
{
    if (!isset($GLOBALS['router']) && !isset($GLOBALS['app'])) {
        throw new \Exception("Not exists router/app in global scope");
    }

    return $GLOBALS['router'] ?? $GLOBALS['app'];
}

function container($id = null)
{
    return is_null($id) ? app()->getContainer() : container()->get($id);
}

function resolve(string $class)
{
    return container()->make($class);
}
