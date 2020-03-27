<?php

namespace Accolon\Route;

class Request
{
    public function __construct()
    {
        foreach($_REQUEST as $key => $value) {
            $this->$key = htmlentities($value);
        }

        foreach (json_decode(file_get_contents('php://input')) ?? [] as $key => $value) {
            $this->$key = htmlentities($value);
        }
    }

    public function get($param)
    {
        return $this->$param ?? null;
    }

    public function set($param, $value)
    {
        if (isset($this->$param)) {
            $this->$param = $value;
        }
    }

    public function getBody()
    {
        return json_decode(file_get_contents('php://input')) ?? [];
    }
}