<?php

namespace Accolon\Route\Rules;

use Accolon\Route\Rule;

class IntegerRule extends Rule
{
    public function check($name, $value): bool
    {
        return preg_match('/[0-9]+/', $value) > 0;
    }

    public function message($name, $value): string
    {
        return "The $name must be an integer";
    }
}
