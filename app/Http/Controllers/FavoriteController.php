<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Favorite\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private $request_only = [
        'flat_id',
    ];

    private static function getWhere()
    {
        $where = [];

        if (
            auth()->user()->role !== 'admin'
        ) {
            $where[] = ['user_id', '=', auth()->id()];
        }

        return $where;
    }


    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(
            Filter::all($request, new Favorite, [], $this::getWhere())
        );
    }


    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $data = Favorite::create([
            ...$request->only($this->request_only),
            'user_id' => $request->user()->id
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    
    public function show(Request $request, int $id): JsonResponse
    {
        return new JsonResponse([
            'data' => Filter::one($request, new Favorite, $id, $this::getWhere())
        ]);
    }


    public function destroy(int $id): JsonResponse
    {
        $favorite = Favorite::findOrFail($id);

        if (AccessUtil::cannot('delete', $favorite)) return AccessUtil::errorMessage();

        Favorite::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
