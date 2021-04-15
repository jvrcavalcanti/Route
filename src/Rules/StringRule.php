<?php

namespace Accolon\Route\Rules;

use Accolon\Route\Rule;

class StringRule extends Rule
{
    public function check($name, $value): bool
    {
        return is_string($value);
    }

    public function message($name, $value): string
    {
        return "The $name must be a string";
    }
}
