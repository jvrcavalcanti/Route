<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Request;
use Accolon\Route\Response;

trait Middlewares
{
    private array $globalMiddlewares;

    public function middlewares(array $middlewares): void
    {
        $this->globalMiddlewares = $middlewares;
    }
}