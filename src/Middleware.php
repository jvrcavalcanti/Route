<?php

namespace Accolon\Route;

use Closure;
use Accolon\Route\Request;
use Accolon\Route\Response;

interface Middleware
{
    public function handle(Request $request, Response $response, Closure $next): ?string;
}
