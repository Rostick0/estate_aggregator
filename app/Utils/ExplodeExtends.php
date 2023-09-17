<?php

namespace App\Utils;

class ExplodeExtends
{
    public static function run($string) {
        if (empty($string)) return [];

        return explode(',', $string);
    }
}