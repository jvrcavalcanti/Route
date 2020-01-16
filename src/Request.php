<?php

namespace Accolon\Route;

class Request
{
    public function __construct()
    {
        foreach($_REQUEST as $key => $value) {
            $this->$key = $value;
        }
    }
}