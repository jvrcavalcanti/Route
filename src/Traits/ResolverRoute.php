<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Request;

trait ResolverRoute
{
    public function resolveRoute($route)
    {
        $reflection = null;
        $params = [];

        if (is_array($route)) {
            $class = $this->container->get($route[0]);
            $method = $route[1];

            $reflection = new \ReflectionClass($class);
            $params = $reflection->getMethod($method)->getParameters();

            $newParams = $this->parseParams($params);

            return ($class)->$method(...$newParams);
        }

        if (is_callable($route)) {
            $reflection = new \ReflectionFunction($route);
            $params = $reflection->getParameters();

            $newParams = $this->parseParams($params);

            return $reflection->getClosure()(...$newParams);
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
                $newParams[] = request($param->name);
                continue;
            }

            if ($type === Request::class || !$param->hasType()) {
                $newParams[] = request();
                continue;
            }

            $newParams[] = $this->container->make($type);
        }

        return $newParams;
    }
}
