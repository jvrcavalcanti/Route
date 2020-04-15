<?php

use Accolon\Route\Middleware;

class JsonResponse extends Middleware
{
    public function handle(\Accolon\Route\Request $request, \Accolon\Route\Response $response, \Closure $next): ?string
    {
        return json_encode($next($request, $response));
    }
}