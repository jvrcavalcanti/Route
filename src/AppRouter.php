<?php

namespace Accolon\Route;

class AppRouter extends Router
{
    public function newRouter()
    {
        dd($this->prefix);
        return new Router($this->container, $this->debug);
    }

    public function router(Router $router)
    {
        foreach ($router->getRoutes() as $method => $list) {
            $this->routes[$method]->merge($list->toArray());
        }
    }
}
