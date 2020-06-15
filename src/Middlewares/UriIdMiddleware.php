<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\Request;
use Accolon\Route\Response;

class UriIdMiddleware implements Middleware
{
    public function handle(Request $request, Response $response, \Closure $next)
    {
        if ($request->has("id")) {
            return $next($request->get("id"), $request, $response);
        }

        return $next($request, $response);
    }
}