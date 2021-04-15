<?php

namespace Accolon\Route\Exceptions;

class BadRequestException extends HttpException
{
    public function __construct($message, int $code = 400, $contentType = 'html')
    {
        if ($code >= 400 && $code <= 500) {
            parent::__construct($message, $code, $contentType);
        }
    }
}