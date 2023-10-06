<?php

namespace App\Utils;

class EmptyUtil
{
    private static function changeVariableType($value, $type)
    {
        $types = (object) [
            'string' => (string) $value,
            'int' => (int) $value,
            'boolean' => (bool) $value,
            'float' => (float) $value,
        ];

        if (isset($types->$type)) return $types->$type;

        return $value;
    }

    public static function valueOrNull($name, $type)
    {
        if (empty($name)) return null;

        return EmptyUtil::changeVariableType($name, $type);
    }

    public static function clearValues(array $object): object
    {
        return (object) collect($object)
            ->filter(function ($value) {
                return !empty($value);
            })->all();
    }
}
