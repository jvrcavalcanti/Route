<?php

namespace Accolon\Route\Exceptions;

class HttpException extends \Exception
{
    protected string $contentType;

    public function __construct(int $code, string $message, string $contentType = "html")
    {
        parent::__construct($message, $code);
        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }
}
