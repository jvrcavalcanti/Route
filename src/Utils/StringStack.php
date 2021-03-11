<?php

namespace Accolon\Route\Utils;

class StringStack
{
    protected array $list = [];

    public function push(string $value)
    {
        $this->list[] = $value;
    }

    public function pop(): string
    {
        $removed = $this->list[$this->count() - 1];
        unset($this->list[$this->count() - 1]);
        return $removed;
    }

    public function count()
    {
        return count($this->list);
    }

    public function empty()
    {
        return empty($this->list);
    }

    public function concatenate(): string
    {
        return array_reduce($this->list, fn($result, $el) => $result . $el, '');
    }
}
