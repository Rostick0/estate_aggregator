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

        $table = $builder->getModel()->getTable();
        $builder->select($table . '.*');

        $sort_name = $name;

        $name_array = explode('.', $name);

        if (isset($name_array[1])) {
            $relat = $builder->getRelation(OrderByUtil::removeMinus($name_array[0]));
            $relat_table = $relat->getModel()->getTable();
            
            $builder->join(
                $relat_table,
                $table . '.' . $relat->getForeignKeyName(),
                '=',
                $relat_table . '.' . $relat->getOwnerKeyName()
            );

            $sort_name = $relat_table . '.' . $name_array[1];
        }

        return $builder->orderBy(
            OrderByUtil::removeMinus($sort_name) ?? 'id',
            OrderByUtil::type($name)
        );
    }
}
