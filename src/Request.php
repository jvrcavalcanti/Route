<?php

namespace Accolon\Route;

use Accolon\Route\Files\File;
use Accolon\Route\Headers\RequestHeaders;
use Accolon\Route\Traits\Files;

class Request
{
    use Files;

    private array $data = [];
    private array $cookie = [];
    private array $files = [];
    public RequestHeaders $headers;

    public function __construct($requests = null)
    {
        foreach ($requests ?? $_REQUEST as $key => $value) {
            $this->data[$key] = htmlentities($value);
        }

        $this->initBody();
        $this->initHeaders();
        $this->cookie = &$_COOKIE;
        $this->files = $this->convertFilesArrayToObject($this->parseFiles($_FILES));
    }
    
    protected function initBody()
    {
        json_decode($this->getBody());

        if (json_last_error() === JSON_ERROR_NONE) {
            foreach (json_decode($this->getBody()) as $key => $value) {
                $this->data[$key] = htmlentities($value);
            }
        }
    }

    protected function initHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        $this->headers = new RequestHeaders($headers);
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

    public function getContentType(): string
    {
        return explode(',', $_SERVER['HTTP_ACCEPT'])[0];
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getFile(string $name): File
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

    public function getUri()
    {
        $uri = urldecode(parse_url($_GET['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $uri == "" ? "/" : $uri;
    }

    public function getCookie(string $name): ?string
    {
        $this->cookie = $_COOKIE;
        return unserialize(base64_decode($this->cookie[$name])) ?? null;
    }

    public function getBody()
    {
        return file_get_contents('php://input') ?? null;
    }
}
