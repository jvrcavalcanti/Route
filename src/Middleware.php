<?php

namespace Accolon\Route;

interface Middleware
{
    public function validate(Request $request, Response $response): bool;
}