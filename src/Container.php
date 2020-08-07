<?php

namespace Accolon\Route;

use Accolon\Route\Routing\Route;
use ReflectionClass;
use ReflectionMethod;

class Container
{
    private array $binds = [];

    public function bind(string $id, $value)
    {
        $this->binds[$id] = $value;
    }

    public function make(string $id)
    {
        if (!$this->has($id)) {
            return $this->resolve($id);
        }

        $value = $this->binds[$id];

        if (is_string($value)) {
            return $this->resolve($value);
        }

        if (is_callable($value)) {
            return call_user_func($value, $this);
        }
    }

    public function has($id)
    {
        return isset($this->binds[$id]);
    }

    public function resolve(string $class)
    {
        $reflector = new ReflectionClass($class);

        $constructor = $reflector->getConstructor() ?? fn() => null;
        $params = ($constructor instanceof ReflectionMethod) ? $constructor->getParameters() : null;

        if ($reflector->isInterface()) {
            throw new \ReflectionException("Interface can't instance");
        }

        if (is_null($params)) {
            return $reflector->newInstance();
        }

        $newParams = [];

        foreach ($params as $param) {
            if ($param->isOptional()) {
                continue;
            }

            $name = (string) $param->getType();

            if ($param->hasType() && (class_exists($name) || interface_exists($name))) {
                $newParams[] = $this->make($name);
                continue;
            }
        }

        return $reflector->newInstance(...$newParams);
    }

    public function resolveRoute(Route $route)
    {
        // $reflector = 
    }
}
