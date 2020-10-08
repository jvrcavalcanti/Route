<?php

use Accolon\Route\Request;
use Accolon\Route\Response;
use Accolon\Route\Router;

function response(): Response
{
    return new Response();
}

function request($param = null)
{
    return is_null($param) ? new Request($_REQUEST) : request()->get($param);
}

function router(): Router
{
    if (!isset($GLOBALS['router'])) {
        throw new \Exception("Not exists router in global scope");
    }

    global $router;
    return $router;
}

function app($id = null)
{
    return is_null($id) ? router()->getContainer() : app()->get($id);
}

function resolve(string $class)
{
    return app()->make($class);
}
