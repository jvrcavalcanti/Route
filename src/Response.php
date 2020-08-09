<?php

namespace Accolon\Route;

class Response
{
    const TEXT = "text/plain";
    const HTML = "text/html";
    const JSON = "application/json";

    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;
    const ERROR = 500;

    private $body;
    private int $code = 200;
    private string $typeContent = "text/plain";
    private string $charset = "UTF-8";
    private $status = [
        200 => "200 OK",
        201 => "201 Created",
        202 => "202 Accepted",
        203 => "203 Non-Authoritative Information",
        204 => "204 Not content",
        205 => "205 Reset Content",
        206 => "206 Partial Content",
        207 => "207 Multi-Status",
        208 => "208 Already Reported",
        226 => "226 IM Used",
        300 => "300 Multiple Choices",
        400 => "400 Bad Request",
        401 => "401 Unauthorized",
        402 => "402 Payment Required",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        406 => "406 Not Acceptable",
        407 => "407 Proxy Authentication Required",
        408 => "408 Request Timeout",
        409 => "409 Confict",
        500 => "500 Internal Server Error",
        501 => "501 Not Implemented",
        502 => "502 Bad Gateway",
        503 => "503 Service Unavailable",
        504 => "504 Gateway Timeout"
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

        if ($result) {
            $this->cookie = $_COOKIE;
        }

        return $result;
    }

    public function setTypeContent(string $type): Response
    {
        $this->typeContent = $type;
        return $this;
    }

    public function status(int $code = 200): Response
    {
        $this->code = $code;
        return $this;
    }

    public function json($body, int $code = 0, array $headers = [])
    {
        return $this->setTypeContent(Response::JSON)->send($body, $code, $headers);
    }

    public function text(string $body, int $code = 0, array $headers = [])
    {
        return $this->setTypeContent(Response::TEXT)->send($body, $code, $headers);
    }

    public function html(string $body, int $code = 0, array $headers = [])
    {
        return $this->setTypeContent(Response::HTML)->send($body, $code, $headers);
    }

    public function send($body, int $code = 0, array $headers = [])
    {
        switch ($this->typeContent) {
            case Response::JSON:
                $this->body = json_encode($body);
                break;
            case Response::HTML:
                $this->body = $body;
                break;
            case Response::TEXT:
                $this->body = $body;
                break;
        }

        $this->setHeaders($headers);

        return $this->status($code == 0 ? $this->code : $code);
    }

    private function header()
    {
        http_response_code($this->code);
        header("Content-Type: {$this->typeContent}; charset={$this->charset}");
        return $this->body;
    }

    public function setHeader(string $name, string $value)
    {
        header("{$name}: {$value}", true);
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    public function run()
    {
        $body = $this->header();

        if (is_array($body) || is_null($body)) {
            return "";
        }

        return $body;
    }
}
