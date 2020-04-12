<?php

namespace Accolon\Route;

use SimpleXMLElement;

class Response
{
    private string $body;
    private int $code;
    private string $typeContent = "text/plain";
    private string $charset = "UTF-8";
    private $status = [
        200 => "200 OK",
        201 => "201 Created",
        204 => "204 Not content",
        400 => "400 Bad Request",
        401 => "401 Unauthorized",
        404 => "404 Not Found",
        409 => "409 Confict",
        500 => "500 Internal Server Error"
    ];

    public function __construct()
    {
        $this->cookie = $_COOKIE;
        header_remove();
    }

    public function setCookie(string $name, $value = null, $options = []): bool
    {
        $result = setcookie(
            $name,
            base64_encode(serialize(htmlentities($value))),
            time() + ($options["expire"] ?? 3600),
            $options["path"] ?? "/"
        );

        if($result) {
            $this->cookie = $_COOKIE;
        }

        return $result;
    }

    public function json($body, int $code = 200): string
    {
        $this->typeContent = "application/json";
        $this->body = json_encode($body);
        $this->code = $code;
        return $this->header();
    }

    public function text(string $body, int $code = 200): string
    {
        $this->typeContent = "text/plain";
        $this->body = $body;
        $this->code = $code;
        return $this->header();
    }

    public function html(string $body, int $code = 200): string
    {
        $this->typeContent = "text/html";
        $this->body = $body;
        $this->code = $code;
        return $this->header();
    }

    private function header()
    {
        http_response_code($this->code);
        header("Content-Type: {$this->typeContent}; charset={$this->charset}");
        header("Status: {$this->status[$this->code]}"); 
        return $this->body;
    }

    public function setHeader(string $name, string $value)
    {
        header("{$name}: {$value}", true);
    }

    public function addHeader(string $name, string $value)
    {
        header("{$name}: {$value}", false);
    }
}