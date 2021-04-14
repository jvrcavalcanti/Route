<?php

namespace Accolon\Route\Attributes;

use Accolon\Route\Enums\Method;

#[\Attribute()]
class Route
{
    public function __construct(
        public string $uri,
        public string $method = Method::GET
    ) {
        //
    }
}
