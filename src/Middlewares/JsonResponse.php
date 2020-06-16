<?php

namespace Accolon\Route\Middlewares;

use Accolon\Route\Middlewares\MiddlewareGlobal;
use Accolon\Route\Response;

class JsonResponse implements MiddlewareGlobal
{
    public function handle(\Accolon\Route\Request $request, \Accolon\Route\Response $response): array
    {
        $response->setTypeContent(Response::JSON);

        return [
            $request,
            $response
        ];
    }
}