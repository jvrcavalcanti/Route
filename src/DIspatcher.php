<?php

namespace Accolon\Route;

use Accolon\Route\Responses\Response;
use Accolon\Route\Routes\RouteCollection;

class Dispatcher
{
    public function __construct(private RouteCollection $collection)
    {
        //
    }

    public function dispatch(Request $request): Response
    {
        $uri = $request->uri();
        $method = $request->method();

        $route = $this->collection->findRoute($method, $uri);
        return $route->run($request);
    }
}
