<?php

namespace Accolon\Route\Exceptions;

class HttpException extends \Exception
{
    protected string $contentType;

    public function __construct($message, int $code, string $contentType = "html")
    {
        parent::__construct($this->typeToString($contentType, $message), $code);
        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    protected function typeToString(string $type, mixed $data)
    {
        return match($type) {
            'html', 'text' => (string) $data,
            'json' => json_encode($data)
        };
    }
}
