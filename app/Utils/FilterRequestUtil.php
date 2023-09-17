<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;

class FilterRequestUtil
{
    public static function eq($request, array $fillable = [])
    {
        $where = [];

        collect($request)->each(function ($value, $key) use (&$where) {
            $where[] = [$key, '=', $value];
            return [$key, '=', $value];
        });

        return $where;
    }

    public static function like($request, array $fillable = [])
    {
        $where = [];

        collect($request)->each(function ($value, $key) use (&$where) {
            $where[] = [$key, 'LIKE', '%' . $value . '%'];
            return [$key, 'LIKE', '%' . $value . '%'];
        });

        return $where;
    }
}
