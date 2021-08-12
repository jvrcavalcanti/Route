<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Enums\ContentType;
use Accolon\Route\Headers;
use Accolon\Route\Traits\Cookie;

abstract class Response
{
    use Cookie;

    public Headers $headers;

    protected string $body;
    protected int $code = 200;
    protected string $typeContent = ContentType::HTML;
    protected string $charset = "UTF-8";

    abstract public function handle($body, int $code = 0, array $headers = []);

    public function __construct()
    {
        $this->headers = new Headers();
        header_remove();
    }

    public function setTypeContent(string $type): self
    {
        $this->typeContent = $type;
        return $this;
    }

    public function status(int $code = 200): self
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

    private function process(): string
    {
        $contentLength = strlen($this->body);
        http_response_code($this->code);
        $this->setHeaders([
            'Content-Type' => "{$this->typeContent}",
            'Accept-Charset' => "{$this->charset}",
            'Content-Length' => "{$contentLength}"
        ]);
        return $this->body;
    }

    public function body(): string
    {
        foreach ($this->headers->data() as $header => $value) {
            header("{$header}: {$value}");
        }
        return $this->process();
    }

    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->headers->set($name, $value);
        }
        return $this;
    }
}
