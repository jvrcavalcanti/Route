<?php

namespace Accolon\Route;

use Accolon\Route\Request;

interface IMiddleware
{
    public function handle(Request $request, $next);
}
