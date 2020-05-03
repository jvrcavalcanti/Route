<?php

namespace Accolon\Route;

abstract class AbstractController
{
    public function validate(Request $request, array $keys): bool
    {
        foreach($keys as $key) {
            if (!in_array($key, $request->all())) {
                return false;
            }
        }

        return true;
    }
}