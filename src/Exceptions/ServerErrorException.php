<?php

namespace Accolon\Route\Exceptions;

class ServerErrorException extends HttpException
{
    public function __construct($message, $code = 500, $contentType = "json")
    {
        parent::__construct($code, $message, $contentType);
    }
}
