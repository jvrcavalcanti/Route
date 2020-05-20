<?php

namespace Accolon\Route;

abstract class AbstractController
{
    public function validate(Request $request, array $keys): bool
    {
        foreach($keys as $key) {
            if (!array_key_exists($key, $request->only($keys))) {
                return false;
            }
        }

        return true;
    }
}