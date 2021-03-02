<?php

namespace Accolon\Route;

class App extends Router
{
    public function newRouter()
    {
        return new Router($this->container, $this->debug);
    }

    public function router(Router $router)
    {
        $this->routes = array_merge($this->routes, $router->getRoutes());
    }
}
