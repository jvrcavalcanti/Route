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

    public static function resolveRule($rule): Rule
    {
        return $rule instanceof Rule ? $rule : resolve(static::VALIDATORS[$rule]);
    }

    public static function request(Request $request, array $rules)
    {
        foreach ($rules as $param => $rule) {
            $message = null;

            $rule = static::resolveRule($rule);

            if (!$request->has($param)) {
                $message = 'Not passed param: ' . $param;
            }

            if (!$rule->check($param, $request->get($param))) {
                $message = $rule->message($param, $request->get($param));
            }

            if ($message) {
                self::$errors[] = $message;
            }
        }

        if (!empty(self::$errors)) {
            throw new ValidateFailException(['errors' => self::$errors]);
        }
    }
}
