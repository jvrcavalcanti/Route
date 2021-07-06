<?php

namespace Accolon\Route;

use Accolon\Route\Response;
use Accolon\Route\Responses\TextResponse;
use Accolon\Route\Responses\HtmlResponse;
use Accolon\Route\Responses\JsonResponse;

class ResponseFactory
{
    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->types())) {
            throw new \Exception('Invalid response type');
        }
        return $this->create($name, ...$arguments);
    }

    protected function types(): array
    {
        return [
            'text' => TextResponse::class,
            'json' => JsonResponse::class,
            'html' => HtmlResponse::class,
        ];
    }

    public function create(string $type, mixed $body, int $code = 0, array $headers = [])
    {
        $class = $this->types()[$type];
        return (new $class())->handle($body, $code, $headers);
    }
}
