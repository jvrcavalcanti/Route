<?php

use Accolon\Route\Request;
use Accolon\Route\Response;

function response(): Response
{
    return new Response();
}

function request($param = null)
{
    return is_null($param) ? new Request($_REQUEST) : request()->get($param);
}
