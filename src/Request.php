<?php

namespace Accolon\Route;

class Request
{
    private $body;

    public function __construct()
    {
        foreach($_REQUEST as $key => $value) {
            $this->$key = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
        }

        foreach (json_decode(file_get_contents('php://input')) ?? [] as $key => $value) {
            $this->$key = $value;
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
        return $this->body;
    }
}