<?php

namespace Accolon\Route;

class Headers
{
    private array $data;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $this->data[$header] = $value;
        }
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
}
