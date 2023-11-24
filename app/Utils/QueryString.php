<?php

namespace App\Utils;

class QueryString
{
    public static function convertToArray($string)
    {
        if (empty($string)) return [];

        return explode(',', $string);
    }
}
