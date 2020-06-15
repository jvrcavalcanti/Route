<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\Request;
use Accolon\Route\Response;

interface MiddlewareGlobal
{
    public function handle(Request $request, Response $response): array;
}