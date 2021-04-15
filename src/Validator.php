<?php

namespace Accolon\Route;

use Accolon\Route\Exceptions\ValidateFailException;
use Accolon\Route\Rules\StringRule;
use Accolon\Route\Rules\IntegerRule;

class Validator
{
    const VALIDATORS = [
        'string' => StringRule::class,
        'int' => IntegerRule::class,
    ];

    protected static array $errors = [];

    public static function make($rule, $name, $value): bool
    {
        if (!$rule instanceof Rule) {
            $rule = resolve(static::VALIDATORS[$rule]);
        }

        return $rule->check($name, $value);
    }

    public static function request(Request $request, array $rules)
    {
        foreach ($rules as $param => $rule) {
            $message = null;

            if (!$request->has($param)) {
                $message = 'Not passed param: ';
            }

            if (!static::make($rule, $param, $request->get($param))) {
                $message = 'Invalided param: ';
            }

            if ($message) {
                self::$errors[] = $message . $param;
            }
        }

        if (!empty(self::$errors)) {
            throw new ValidateFailException(self::$errors);
        }
    }
}
