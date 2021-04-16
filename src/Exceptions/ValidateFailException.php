<?php

namespace Accolon\Route\Exceptions;

class ValidateFailException extends BadRequestException
{
    public function __construct($message, $code = 400, $contentType = "json")
    {
        parent::__construct($message, $code, $contentType);
    }
}
