<?php

namespace Accolon\Route\Headers;

class RequestHeaders
{
    private array $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get($name)
    {
        return $this->data[$name] ?? null;
    }
}
