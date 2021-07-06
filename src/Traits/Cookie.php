<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Exceptions\CookieNotFoundException;

trait Cookie
{
    public function hasCookie(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function getCookie(string $name): mixed
    {
        if (!$this->hasCookie($name)) {
            throw new CookieNotFoundException("Cookie with name {$name} not found");
        }

        return json_decode($_COOKIE[$name]);
    }

    public function setCookie(
        string $name,
        mixed $value,
        ?int $expires = null
    ): bool {
        return setcookie($name, json_encode($value), $expires ?? time() + 60 * 60, '/');
    }
}
