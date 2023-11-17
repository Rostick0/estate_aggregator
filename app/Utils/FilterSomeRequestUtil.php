<?php

namespace App\Utils;
use Illuminate\Database\Eloquent\Builder;

class FilterSomeRequestUtil
{
    /**
     * @param class-string<"NULL"|"LIKE"> $type_where
     */

    public static function template($request, Builder $builder, array $fillable = [], $type = '=', ?string $type_where = "NULL|LIKE"): Builder
    {
        collect($request)->each(function ($value, $key) use ($builder, $fillable, $type, $type_where) {
            // dd($key);

            if (!empty($fillable) && array_search($key, $fillable) === false) return;

            $where = [];

            // if (!isset($value)) {
            // } else if ($type_where === 'NULL') {
            //     $where[] = [$key, $type, NULL];
            // } else if ($type_where === 'LIKE') {
            //     $where[] = [$key, 'LIKE', '%' . $value . '%'];
            // } else {
            //     $where[] = [$key, $type, $value];
            // }

            $builder->whereHas($key, function ($query) use ($where) {
                // $query->where($where);
            });
        });

        return $builder;
    }

    public static function all($request, Builder $builder, array $fillable = []): Builder
    {
        $data = $builder;

        if ($request->filterSomeEQ) $data = FilterSomeRequestUtil::template($request->filterSomeEQ, $builder, $fillable, '=');
        if ($request->filterSomeNEQ) $data = FilterSomeRequestUtil::template($request->filterSomeNEQ, $builder, $fillable, '!=');

        if ($request->filterSomeEQN) $data = FilterSomeRequestUtil::template($request->filterSomeEQN, $builder, $fillable, '=', 'NULL');
        if ($request->filterSomeNEQN) $data = FilterSomeRequestUtil::template($request->filterSomeNEQN, $builder, $fillable, '!=', 'NULL');

        if ($request->filterSomeCEQ) $data = FilterSomeRequestUtil::template($request->filterSomeCEQ, $builder, $fillable, '>=');
        if ($request->filterSomeLEQ) $data = FilterSomeRequestUtil::template($request->filterSomeLEQ, $builder, $fillable, '<=');
        if ($request->filterSomeCE) $data = FilterSomeRequestUtil::template($request->filterSomeCE, $builder, $fillable, '>');
        if ($request->filterSomeLE) $data = FilterSomeRequestUtil::template($request->filterSomeLE, $builder, $fillable, '<');

        if ($request->filterSomeLIKE) $data = FilterSomeRequestUtil::template($request->filterSomeLIKE, $builder, $fillable, 'LIKE', 'LIKE');

        return $data;
    }
}
