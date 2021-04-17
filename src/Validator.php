<?php

namespace Accolon\Route;

use Accolon\Route\Exceptions\ValidateFailException;
use Accolon\Route\Rules\FloatRule;
use Accolon\Route\Rules\StringRule;
use Accolon\Route\Rules\IntegerRule;

class Validator
{
    const VALIDATORS = [
        'string' => StringRule::class,
        'int' => IntegerRule::class,
        'float' => FloatRule::class,
    ];

    protected static array $errors = [
        'missings' => [],
        'invalids' => []
    ];

    public static function resolveRule($rule): Rule
    {
        return $rule instanceof Rule ? $rule : resolve(static::VALIDATORS[$rule]);
    }

    public static function request(Request $request, array $rules)
    {
        foreach ($rules as $param => $rule) {
            $rule = static::resolveRule($rule);

            if (!$request->has($param)) {
                self::$errors['missings'][] = "Missing parameter [{$param}]";
                continue;
            }

            if (!$rule->check($param, $request->get($param))) {
                self::$errors['invalids'][] = $rule->message($param, $request->get($param));
            }
        }

        if (!empty(self::$errors['missings']) || !empty(self::$errors['invalids'])) {
            throw new ValidateFailException(['errors' => self::$errors]);
        }
    }
}
