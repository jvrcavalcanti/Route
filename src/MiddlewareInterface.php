<?php

namespace Accolon\Route;

use Accolon\Route\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, $next);
}
