<?php

namespace Accolon\Route;

interface MiddlewareGlobal
{
    public function handle(Request $request, Response $response): array;
}