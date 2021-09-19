<?php

namespace Accolon\Route\Rules;

interface RuleInterface
{
    public function check($name, $value): bool;
    public function message($name, $value): string;
}
