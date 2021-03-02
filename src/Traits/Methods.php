<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Route;

trait Methods
{
    public function get(string $url, $action): Route
    {
        return $this->addRoute('GET', $url, $action);
    }
    
    public function post(string $url, $action): Route
    {
        return $this->addRoute('POST', $url, $action);
    }

    public function put(string $url, $action): Route
    {
        return $this->addRoute('PUT', $url, $action);
    }

    public function patch(string $url, $action): Route
    {
        return $this->addRoute('PATCH', $url, $action);
    }

    public function delete(string $url, $action): Route
    {
        return $this->addRoute('DELETE', $url, $action);
    }

    public function options(string $url, $action): Route
    {
        return $this->addRoute('OPTIONS', $url, $action);
    }
    
    public function head(string $url, $action): Route
    {
        return $this->addRoute('HEAD', $url, $action);
    }

    public function fallback($handler)
    {
        if (is_array($handler)) {
            $this->fallback = \Closure::fromCallable([
                $this->container->make($handler[0]),
                $handler[1]
            ]);
        }
        
        if (is_callable($handler)) {
            $this->fallback = $handler;
        }
    }
}
