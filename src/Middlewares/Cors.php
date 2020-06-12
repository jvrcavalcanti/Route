<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\MiddlewareGlobal;

class Cors implements MiddlewareGlobal
{
    public function handle(\Accolon\Route\Request $request, \Accolon\Route\Response $response): array
    {
        $response->setHeader("Access-Control-Allow-Origin", "*");

        return [
            $request,
            $response
        ];
    }
}