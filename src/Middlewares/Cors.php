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

        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Methods", 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set("Access-Control-Max-Age", 3600);
        $response->headers->set("Access-Control-Allow-Headers", 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        return $response;
    }
}
