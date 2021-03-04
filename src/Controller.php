<?php

namespace Accolon\Route;

abstract class Controller
{
    protected function validate(array $rules)
    {
        Validator::request(request(), $rules);
    }
}
