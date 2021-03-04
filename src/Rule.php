<?php

namespace Accolon\Route;

abstract class Rule
{
    abstract public function check($name, $value): bool;
    abstract public function message($name, $value): string;
}
