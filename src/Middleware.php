<?php

namespace Accolon\Route;

interface Middleware
{
    public function handle(Request $request, Response $response): bool;
}