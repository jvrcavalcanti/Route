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
        400 => "400 Bad Request",
        401 => "401 Unauthorized",
        404 => "404 Not Found",
        500 => "500 Internal Server Error"
    ];

    public function __construct()
    {
        header_remove();
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
}