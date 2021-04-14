<?php

namespace Accolon\Route\Enums;

abstract class Status
{
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const ERROR = 500;
}
