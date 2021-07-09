<?php

namespace Accolon\Route;

use Accolon\Route\Files\File;
use Accolon\Route\Headers;
use Accolon\Route\Traits\Cookie;
use Accolon\Route\Traits\Files;

class Request
{
    use Files, Cookie;

    private array $data = [];
    private array $cookie = [];
    private array $files = [];
    public Headers $headers;

    public function __construct($requests = null)
    {
        foreach ($requests ?? $_REQUEST as $key => $value) {
            $this->data[$key] = htmlentities($value);
        }

        $this->initBody();
        $this->initHeaders();
        $this->files = $this->convertFilesArrayToObject($this->parseFiles($_FILES));
    }
    
    protected function initBody()
    {
        json_decode($this->body());

        if (json_last_error() === JSON_ERROR_NONE) {
            foreach (json_decode($this->body()) as $key => $value) {
                $this->data[$key] = htmlentities($value);
            }
        }
    }

    protected function initHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        $this->headers = new Headers($headers);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get(string $param): ?string
    {
        return $this->data[$param] ?? ($_REQUEST[$param] ?? null);
    }

    public function has(string $param)
    {
        return isset($this->data[$param]);
    }

    public function only(array $keys): array
    {
        return array_filter($this->data, function ($key) use ($keys) {
            if (in_array($key, $keys)) {
                return $this->data[$key];
            }
        }, ARRAY_FILTER_USE_KEY);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
    }

    public function contentType(): string
    {
        return $this->headers->get('Content-Type');
    }

    public function files(): array
    {
        return $this->files;
    }

    public function file(string $name): File
    {
        if (!isset($this->files[$name])) {
            throw new \OutOfBoundsException("File with name [{$name}] not exists");
        }

        return $this->files[$name];
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod(string $method): bool
    {
        return $method === $this->method();
    }

    public function authorization(): ?string
    {
        return $this->headers->get('Authorization') ?? null;
    }

    public function uri(): string
    {
        $uri = urldecode(parse_url($_GET['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $uri == "" ? "/" : $uri;
    }

    public function body(): string|false
    {
        return file_get_contents('php://input');
    }
}
