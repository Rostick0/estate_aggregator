<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;
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

    public static function has($request, Builder $model)
    {
        collect($request)->each(function ($item, $name) use ($model) {
            $where = [];
            foreach (Json::decode($item) as $key => $value) {
                $where[] = [$key, '=', $value];
            }

            $model->whereHas($name, function ($query) use ($where) {
                $query->where($where);
            });
        });

        return $model;
    }
}
