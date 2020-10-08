<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Provider;

trait Providers
{
    /** @property Provider[] */
    private array $providers = [];

    public function registerProvider(string $class)
    {
        $provider = $this->container->make($class);
        $provider->register();
        $this->providers[] = $provider;
    }

    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->registerProvider($provider);
        }
    }

    public function bootProviders()
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }
}
