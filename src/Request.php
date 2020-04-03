<?php

namespace Accolon\Route;

class Request
{
    private array $data = [];
    private array $cookie = [];
    private array $files = [];

    public function __construct()
    {
        foreach($_REQUEST as $key => $value) {
            $this->data[$key] = htmlentities($value);
        }

        foreach (json_decode($this->getBody()) as $key => $value) {
            $this->data[$key] = htmlentities($value);
        }

        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
    }

    public function get(string $param)
    {
        return $this->data[$param] ?? null;
    }

    public function getFile(string $name)
    {
        return $this->files[$name] ?? null;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getCookie(string $name): ?string
    {
        $this->cookie = $_COOKIE;
        return unserialize(base64_decode($this->cookie[$name])) ?? null;
    }

    public function getBody()
    {
        return file_get_contents('php://input') ?? [];
    }
}