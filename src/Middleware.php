<?php

namespace Accolon\Route;

use Closure;
use Accolon\Route\Request;
use Accolon\Route\Response;

abstract class Middleware
{
    abstract public function handle(Request $request, Response $response, Closure $next): ?string;
}
