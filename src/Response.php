<?php

namespace Accolon\Route;

use Accolon\Route\Enums\ContentType;
use Accolon\Route\Headers;
use Accolon\Route\Traits\Cookie;

abstract class Response
{
    use Cookie;

    public Headers $headers;

    protected $body;
    protected int $code = 200;
    protected string $typeContent = ContentType::HTML;
    protected string $charset = "UTF-8";
    protected $status = [
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

    abstract public function handle($body, int $code = 0, array $headers = []);

    public function __construct()
    {
        $this->cookie = $_COOKIE;
        $this->headers = new Headers();
        header_remove();
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

    protected function send(string $body, int $code = 0, array $headers = [])
    {
        $this->body = $body;
        $this->setHeaders($headers);
        return $this->status($code == 0 ? $this->code : $code);
    }

    private function header()
    {
        $contentLength = strlen($this->body);
        http_response_code($this->code);
        header("Content-Type: {$this->typeContent}", $this->code);
        header("Accept-Charset: {$this->charset}");
        header("Content-Length: {$contentLength}");
        return $this->body;
    }

    public function run()
    {
        $body = $this->header();

        if (is_array($body) || is_null($body)) {
            return "";
        }

        return $body;
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->headers->set($name, $value);
        }
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
}
