<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\builder;

class FilterRequestUtil
{
    // public static function eq($request, array $fillable = [])
    // {
    //     $where = [];

    //     collect($request)->each(function ($value, $key) use (&$where) {
    //         $where[] = [$key, '=', $value];
    //         return [$key, '=', $value];
    //     });

    //     return $where;
    // }

    /**
     * @param class-string<"NULL"|"LIKE"> $type_where
     */

    public static function template($request, Builder $builder, array $fillable = [], $type = '=', ?string $type_where = "NULL|LIKE"): Builder
    {
        collect($request)->each(function ($value, $key) use ($builder, $fillable, $type, $type_where) {
            if (FilterTypeUtil::check($key)) return;

            if (!empty($fillable) && array_search($key, $fillable) === false) return;
            $where = [];


            if (!isset($value)) {
            } else if ($type_where === 'NULL') {
                $where[] = [$key, $type, NULL];
            } else if ($type_where === 'LIKE') {
                $where[] = [$key, 'LIKE', '%' . $value . '%'];
            } else {
                $where[] = [$key, $type, $value];
            }

            $builder->where($where);
        });

        return $builder;
    }

    public static function in($request, Builder $builder, array $fillable = []): Builder
    {
        collect($request)->each(function ($value, $key) use ($builder, $fillable) {
            if (FilterTypeUtil::check($key)) return;
           
            if (!empty($fillable) && array_search($key, $fillable) === false) return;
            $where = QueryString::convertToArray($value);

            $builder->whereIn($key, $where);
        });

        return $builder;
    }

    public static function all($request, Builder $builder, array $fillable = []): Builder
    {
        $data = $builder;

        if ($request->filterEQ) $data = FilterRequestUtil::template($request->filterEQ, $builder, $fillable, '=');
        if ($request->filterNEQ) $data = FilterRequestUtil::template($request->filterNEQ, $builder, $fillable, '!=');

        if ($request->filterEQN) $data = FilterRequestUtil::template($request->filterEQN, $builder, $fillable, '=', 'NULL');
        if ($request->filterNEQN) $data = FilterRequestUtil::template($request->filterNEQN, $builder, $fillable, '!=', 'NULL');

        if ($request->filterCEQ) $data = FilterRequestUtil::template($request->filterCEQ, $builder, $fillable, '>=');
        if ($request->filterLEQ) $data = FilterRequestUtil::template($request->filterLEQ, $builder, $fillable, '<=');
        if ($request->filterCE) $data = FilterRequestUtil::template($request->filterCE, $builder, $fillable, '>');
        if ($request->filterLE) $data = FilterRequestUtil::template($request->filterLE, $builder, $fillable, '<');

        if ($request->filterLIKE) $data = FilterRequestUtil::template($request->filterLIKE, $builder, $fillable, 'LIKE', 'LIKE');

        if ($request->filterIN) $data = FilterRequestUtil::in($request->filterIN, $builder, $fillable);

        return $data;
    }
}
