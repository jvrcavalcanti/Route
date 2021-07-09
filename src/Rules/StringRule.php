<?php

namespace Accolon\Route\Rules;

class StringRule implements RuleInterface
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
