<?php

namespace Accolon\Route\Exceptions;

class HttpException extends \Exception
{
    protected string $contentType;
    protected $message;

    public function __construct($message, int $code, string $contentType = "html")
    {
        parent::__construct('', $code);
        $this->message = $message;
        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }
}
