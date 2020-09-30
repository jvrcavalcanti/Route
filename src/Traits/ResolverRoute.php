<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Request;
use Accolon\Route\Response;

trait ResolverRoute
{
    public function resolveRoute($route)
    {
        $reflection = null;
        $params = [];

        if (is_callable($route)) {
            $reflection = new \ReflectionFunction($route);
            $params = $reflection->getParameters();

            $newParams = $this->parseParams($params);

            return $reflection->getClosure()(...$newParams);
        }

        if (is_array($route)) {
            $class = $route[0];
            $method = $route[1];

            $reflection = new \ReflectionClass($class);
            $params = $reflection->getMethod($method)->getParameters();

            $newParams = $this->parseParams($params);

            return ($this->container->make($class))->$method(...$newParams);
        }
    }

    public function parseParams(array $params)
    {
        $newParams = [];

        foreach ($params as $param) {
            $type = (string) $param->getType();

            if ($param->isOptional()) {
                continue;
            }

            if (request()->has($param->name)) {
                $newParams[$param->name] = request($param->name);
                continue;
            }

            if ($type === Request::class) {
                $newParams[$param->name] = request();
                continue;
            }

            $newParams[$param->name] = $this->container->make($type);
        }

        return $newParams;
    }
}
