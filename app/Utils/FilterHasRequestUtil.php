<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;

class FilterHasRequestUtil
{
    /**
     * @param class-string<"NULL"|"LIKE"> $type_where
     */

    public static function template($request, Builder $builder, array $fillable = [], $type = '=', ?string $type_where = "NULL|LIKE"): Builder
    {
        collect($request)->each(function ($value, $name) use ($builder, $fillable, $type, $type_where) {
            if (!FilterTypeUtil::check($name)) return;

            $name_array = explode('.', $name);
            $key = array_splice($name_array, -1, 1)[0];
            $name_has = implode('.', $name_array);

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

            $builder->whereHas($name_has, function ($query) use ($where) {
                $query->where($where);
            });
        });

        return $builder;
    }

    public static function in($request, Builder $builder, array $fillable = []): Builder
    {
        collect($request)->each(function ($value, $name) use ($builder, $fillable) {
            if (!FilterTypeUtil::check($name)) return;

            $name_array = explode('.', $name);
            $key = array_splice($name_array, -1, 1)[0];
            $name_has = implode('.', $name_array);

            if (!empty($fillable) && array_search($key, $fillable) === false) return;

            $builder->whereHas($name_has, function ($query) use ($key, $value) {
                $query->whereIn($key, QueryString::convertToArray($value));
            });
        });

        return $builder;
    }

    public static function all($request, Builder $builder, array $fillable = []): Builder
    {
        $data = $builder;

        if ($request->filterEQ) $data = FilterHasRequestUtil::template($request->filterEQ, $builder, $fillable, '=');
        if ($request->filterNEQ) $data = FilterHasRequestUtil::template($request->filterNEQ, $builder, $fillable, '!=');

        if ($request->filterEQN) $data = FilterHasRequestUtil::template($request->filterEQN, $builder, $fillable, '=', 'NULL');
        if ($request->filterNEQN) $data = FilterHasRequestUtil::template($request->filterNEQN, $builder, $fillable, '!=', 'NULL');

        if ($request->filterCEQ) $data = FilterHasRequestUtil::template($request->filterCEQ, $builder, $fillable, '>=');
        if ($request->filterLEQ) $data = FilterHasRequestUtil::template($request->filterLEQ, $builder, $fillable, '<=');
        if ($request->filterCE) $data = FilterHasRequestUtil::template($request->filterCE, $builder, $fillable, '>');
        if ($request->filterLE) $data = FilterHasRequestUtil::template($request->filterLE, $builder, $fillable, '<');

        if ($request->filterLIKE) $data = FilterHasRequestUtil::template($request->filterLIKE, $builder, $fillable, 'LIKE', 'LIKE');

        if ($request->filterIN) $data = FilterHasRequestUtil::in($request->filterIN, $builder, $fillable);

        return $data;
    }
}
