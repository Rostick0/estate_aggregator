<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;

class FilterSomeRequestUtil
{
    /**
     * @param class-string<"NULL"|"LIKE"> $type_where
     */

    public static function template($request, Builder $builder, array $fillable = [], $type = '=', ?string $type_where = "NULL|LIKE"): Builder
    {
        collect($request)->each(function ($value, $key) use ($builder, $fillable, $type, $type_where) {
            $values = Json::decode($value, false);
            // dd($values);

            if (!empty($fillable) && array_search($key, $fillable) === false) return;

            if (!is_array($values)) {
                $values = [$values];
            }

            foreach ($values as $once) {
                $builder = FilterSomeRequestUtil::once(
                    $type_where,
                    $once->column_value ?? null,
                    $once->value ?? null,
                    $type,
                    $builder,
                    $key,
                    [
                        [$once->column_id ?? null, '=', $once->id ?? null]
                    ]
                );
            }
        });

        return $builder;
    }

    private static function once($type_where, $column_value, $value, $type, $builder, $key, $where)
    {
        if (!isset($value)) {
        } else if ($type_where === 'NULL') {
            $where[] = [$column_value, $type, NULL];
        } else if ($type_where === 'LIKE') {
            $where[] = [$column_value, 'LIKE', '%' . $value . '%'];
        } else {
            $where[] = [$column_value, $type, $value];
        }

        return $builder->whereHas($key, function ($query) use ($where) {
            $query->where($where);
        });
    }

    public static function all($request, Builder $builder, array $fillable = []): Builder
    {
        $data = $builder;

        if ($request->filterSomeEQ) $data = FilterSomeRequestUtil::template($request->filterSomeEQ, $builder, $fillable, '=');
        if ($request->filterSomeNEQ) $data = FilterSomeRequestUtil::template($request->filterSomeNEQ, $builder, $fillable, '!=');

        if ($request->filterSomeEQN) $data = FilterSomeRequestUtil::template($request->filterSomeEQN, $builder, $fillable, '=', 'NULL');
        if ($request->filterSomeNEQN) $data = FilterSomeRequestUtil::template($request->filterSomeNEQN, $builder, $fillable, '!=', 'NULL');

        if ($request->filterSomeGEQ) $data = FilterSomeRequestUtil::template($request->filterSomeGEQ, $builder, $fillable, '>=');
        if ($request->filterSomeLEQ) $data = FilterSomeRequestUtil::template($request->filterSomeLEQ, $builder, $fillable, '<=');
        if ($request->filterSomeGE) $data = FilterSomeRequestUtil::template($request->filterSomeGE, $builder, $fillable, '>');
        if ($request->filterSomeLE) $data = FilterSomeRequestUtil::template($request->filterSomeLE, $builder, $fillable, '<');

        if ($request->filterSomeLIKE) $data = FilterSomeRequestUtil::template($request->filterSomeLIKE, $builder, $fillable, 'LIKE', 'LIKE');

        return $data;
    }
}
