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
