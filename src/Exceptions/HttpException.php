<?php

namespace Accolon\Route\Exceptions;

class HttpException extends \Exception
{
    protected string $contentType;
    protected $message;

    public function __construct(int $code, $message, string $contentType = "html")
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
