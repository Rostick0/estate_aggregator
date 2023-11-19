<?php

namespace App\Utils;

class RandomUtil
{
    public static function array($values)
    {
        return $values[random_int(0, count($values) - 1)];
    }
}
