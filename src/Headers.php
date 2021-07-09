<?php

namespace Accolon\Route;

class Headers
{
    private array $data;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
        header("{$name}: {$value}", true);
    }

    public function data(): array
    {
        return $this->data;
    }
}
