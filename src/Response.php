<?php

namespace Accolon\Route;

class Response
{
    private $body;
    private $code;
    private $typeContent = "text/plain";
    private $charset = "UTF-8";
    private $status = [
        200 => "200 OK",
        201 => "201 Created",
        400 => "400 Bad Request",
        404 => "404 Not Found",
        500 => "500 Internal Server Error"
    ];

    public function __construct()
    {
        header_remove();
        return $this;  
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

    private function header()
    {
        http_response_code($this->code);
        header("Content-Type: {$this->typeContent}; charset={$this->charset}");
        header("Status: {$this->status[$this->code]}"); 
        return $this->body;
    }
}