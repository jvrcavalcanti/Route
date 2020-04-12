<?php

namespace Accolon\Route;

class Request
{
    private array $data = [];
    private array $cookie = [];
    private array $files = [];
    private array $headers = [];

    public function __construct()
    {
        foreach($_REQUEST as $key => $value) {
            $this->data[$key] = htmlentities($value);
        }

        $body = json_decode($this->getBody());

        if(is_array($body) || is_object($body)) {
            foreach (json_decode($this->getBody()) as $key => $value) {
                $this->data[$key] = htmlentities($value);
            }
        }

        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->headers = $_SERVER;
    }

    public function get(string $param)
    {
        return $this->data[$param] ?? ($_REQUEST[$param] ?? null);
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
    }

    public function getHeader(string $name)
    {
        return $_SERVER[$name];
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