<?php

namespace App\Filters;

use App\Utils\FilterHasRequestUtil;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use App\Utils\QueryString;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class Filter
{
    public static function all($request, Model $model, array $fillable = [], array $where = []): Paginator
    {
        $data = $model->with(QueryString::convertToArray($request->extends));
        $data = FilterRequestUtil::all($request, $data, $fillable);
        $data = FilterHasRequestUtil::all($request, $data, $fillable);
        $data = OrderByUtil::set($request->sort, $data);

        if ($where) $data->where($where);

        $data = $data->paginate($request->limit);

        return $data;
    }

    public static function one($request, Model $model, int $id, array $where = [])
    {
        $data = $model->with(QueryString::convertToArray($request->extends))
            ->where($where)
            ->findOrFail($id);

        return $data;
    }
}
