<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\Middleware;
use Accolon\Route\Request;
use Accolon\Route\Response;

class Cors implements Middleware
{
    public function handle(Request $request, Response $response, $next)
    {
        $response = $next($request, $response);

        if (!$request->isMethod('OPTIONS')) {
            dd($response);
            return $response;
        }

        $response->setHeader("Access-Control-Allow-Origin", "*");
        $response->setHeader("Access-Control-Allow-Methods", 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader("Access-Control-Max-Age", 3600);
        $response->setHeader("Access-Control-Allow-Headers", 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        return $response;
    }
}
