<?php

namespace Accolon\Route;

use Accolon\Route\Middleware;

class PatternMiddleware implements Middleware
{
    public function validate(Request $request, Response $response): bool {
        return true;
    }
}