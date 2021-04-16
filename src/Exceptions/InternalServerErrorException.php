<?php

namespace Accolon\Route\Exceptions;

class InternalServerErrorException extends HttpException
{
    public function __construct($message, $code = 500, $contentType = "json")
    {
        if ($code >= 500 && $code < 600) {
            parent::__construct($code, $message, $contentType);
        }
    }
}
