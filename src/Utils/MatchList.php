<?php

namespace Accolon\Route\Utils;

class MatchList implements \ArrayAccess
{
    private array $list = [];
    private string $prefix;
    private string $suffix;

    public function __construct(string $prefix = '/^', string $suffix = '$/')
    {
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function offsetExists($subject)
    {
        try {
            (string) $subject;
        } catch (\Exception $e) {
            return false;
        }

        foreach ($this->list as $pattern => $value) {
            if (preg_match_all($pattern, $subject)) {
                return true;
            }
        }

        return false;
    }

    public function offsetGet($subject)
    {
        try {
            (string) $subject;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Subject must be a string');
        }

        foreach ($this->list as $pattern => $value) {
            if (preg_match_all($pattern, $subject)) {
                return $value;
            }
        }

        return null;
    }

    public function offsetSet($pattern, $value)
    {
        $this->list[$this->prefix . $pattern . $this->suffix] = $value;
    }

    public function offsetUnset($pattern)
    {
        unset($this->list[$this->prefix . $pattern . $this->suffix]);
    }
}
