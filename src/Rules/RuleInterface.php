<?php

namespace Accolon\Route\Rules;

interface Rule
{
    public function check($name, $value): bool;
    public function message($name, $value): string;
}
