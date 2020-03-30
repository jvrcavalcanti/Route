<?php

namespace Accolon\Route;

abstract class MiddlewareGlobal
{
    abstract public function handle(Request $request, Response $response): array;
}