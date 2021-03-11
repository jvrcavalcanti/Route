<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Utils\StringStack;

trait Prefix
{
    protected StringStack $prefix;

    protected function getPrefix()
    {
        return $this->prefix->concatenate();
    }

    public function pushPrefix(string $prefix)
    {
        $this->prefix->push($prefix);
        return $this;
    }

    public function popPrefix()
    {
        $this->prefix->pop();
        return $this;
    }
}
