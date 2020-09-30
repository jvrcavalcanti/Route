<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\IMiddleware;
use Accolon\Route\Request;

class Cors implements IMiddleware
{
    public function handle(Request $request, $next)
    {

        if (!$request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $response = response()->status(200);

        $response->setHeader("Access-Control-Allow-Origin", "*");
        $response->setHeader("Access-Control-Allow-Methods", 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader("Access-Control-Max-Age", 3600);
        $response->setHeader("Access-Control-Allow-Headers", 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        return $response;
    }
}
