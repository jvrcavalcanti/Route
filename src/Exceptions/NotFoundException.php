<?php

namespace Accolon\Route\Exceptions;

class NotFoundException extends BadRequestException
{
    public function __consctruct()
    {
        parent::__consctruct('', 404);
    }
}
