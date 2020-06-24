<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\Middleware;

class Cors implements Middleware
{
    public function handle(\Accolon\Route\Request $request, \Accolon\Route\Response $response, $next)
    {
        $response->setHeader("Access-Control-Allow-Origin", "*");

        return $next($request, $response);
    }
}