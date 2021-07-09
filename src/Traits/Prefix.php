<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Utils\StringStack;

trait Prefix
{
    protected StringStack $prefix;

    protected function initPrefix(?string $initialPrefix = null)
    {
        $this->prefix = new StringStack();
        if (!is_null($initialPrefix)) {
            $this->pushPrefix($initialPrefix);
        }
    }

    protected function getPrefix()
    {
        return $this->prefix->concatenate();
    }

    public function prefix(string $prefix)
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
