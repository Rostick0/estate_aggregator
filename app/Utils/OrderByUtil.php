<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;

class OrderByUtil
{
    private static function checkMinus(string $name): bool
    {
        return $name[0] == '-';
    }

    private static function type(string $name): string
    {
        if (OrderByUtil::checkMinus($name)) return 'ASC';

        return 'DESC';
    }

    private static function removeMinus(string $name): string
    {
        if (OrderByUtil::checkMinus($name)) return substr($name, 1);

        return $name;
    }

    public static function set(?string $name, Builder $builder): Builder
    {
        if (!$name) return $builder;

        return $builder->orderBy(
            OrderByUtil::removeMinus($name) ?? 'id',
            OrderByUtil::type($name)
        );
    }
}
