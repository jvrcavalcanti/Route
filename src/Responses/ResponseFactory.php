<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Responses\TextResponse;
use Accolon\Route\Responses\HtmlResponse;
use Accolon\Route\Responses\JsonResponse;

class ResponseFactory
{
    protected array $types = [
        'text' => TextResponse::class,
        'json' => JsonResponse::class,
        'html' => HtmlResponse::class,
    ];

    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->types)) {
            throw new \Exception('Invalid response type');
        }

        if (!isset($arguments[1])) {
            $arguments[1] = 200;
        }

        return $this->create($name, ...$arguments);
    }

    public function create(string $type, mixed $body, int $code = 0, array $headers = []): Response
    {
        $class = $this->types[$type];
        return (new $class())->handle($body, $code, $headers);
    }
}
