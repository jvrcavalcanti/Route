<?php

namespace Accolon\Route;

use Accolon\Container\Container;

abstract class Provider
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }
}
