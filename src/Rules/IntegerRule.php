<?php

namespace Accolon\Route\Rules;

class IntegerRule implements RuleInterface
{
    public function check($name, $value): bool
    {
        return preg_match('/[0-9]+/', $value) > 0;
    }

    public function message($name, $value): string
    {
        return "The $name must be an integer number";
    }
}
