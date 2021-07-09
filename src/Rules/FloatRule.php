<?php

namespace Accolon\Route\Rules;

class FloatRule implements RuleInterface
{
    public function check($name, $value): bool
    {
        return preg_match('/[0-9]{0,}[\.]{1}[0-9]{1,}+/', $value) > 0;
    }

    public function message($name, $value): string
    {
        return "The $name must be an float number";
    }
}
