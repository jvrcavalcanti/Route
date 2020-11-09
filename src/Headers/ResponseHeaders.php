<?php

namespace Accolon\Route\Headers;

class ResponseHeaders
{
    private array $headers = [];

    public function set($name, $value)
    {
        $this->headers[$name] = $value;
        header("{$name}: {$value}", true);
    }
}
