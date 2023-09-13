<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flat\ShowFlatRequest;
use App\Models\Flat;
use App\Http\Requests\Flat\StoreFlatRequest;
use App\Http\Requests\Flat\UpdateFlatRequest;
use App\Utils\ImageDBUtil;
use Illuminate\Http\JsonResponse;

class FlatController extends Controller
{

    public function index()
    {
        //
    }

    public function store(StoreFlatRequest $request)
    {
        //
    }

    public function show(ShowFlatRequest $request, int $id)
    {
        $flat = Flat::with($request->extends ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $flat
            ],
        );
    }

    public function update(UpdateFlatRequest $request, int $id)
    {
        //
    }

    public function destroy(int $id)
    {
        $flat = Flat::findOrFail($id);

        if (auth()->check() && auth()?->user()?->cannot('delete', $flat)) return abort(403, 'No access');

        $delete_image_ids = collect($flat->images())->map(function ($item) {
            return $item->id;
        });
        ImageDBUtil::deleteImage([...$delete_image_ids], $id);
        Flat::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ],
            204
        );
    }
}
