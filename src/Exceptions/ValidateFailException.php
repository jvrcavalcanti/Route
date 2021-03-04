<?php

namespace Accolon\Route\Exceptions;

class ValidateFailException extends HttpException
{
    public function __construct($message, $code = 400, $contentType = "json")
    {
        parent::__construct($code, $message, $contentType);
    }
}
