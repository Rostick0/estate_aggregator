<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\IndexFileRequest;
use App\Models\File;
use App\Http\Requests\File\StoreFileRequest;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{

    public function index(IndexFileRequest $request)
    {
        $data_init = File::with(QueryString::convertToArray($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));
        $data_init = OrderByUtil::set($request->sort, $data_init);

        $data = $data_init->paginate($request->limit ?? 50);

        return new JsonResponse($data);
    }

    public function store(StoreFileRequest $request)
    {
        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();
        $random_name = 'upload/' . random_int(1000, 9999) . time() . '.' . $extension;

        $file->storeAs('public/' . $random_name);

        $data = File::create([
            'name' =>  $file->getClientOriginalName(),
            'path' => $random_name,
            'type' => $file->getClientMimeType(),
            'user_id' => auth()->id(),
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    public function show(int $id)
    {
        $file = File::findOrFail($id);

        return new JsonResponse([
            'data' => $file
        ]);
    }

    public function update()
    {
        return abort(404);
    }

    public function destroy(int $id)
    {
        $file = File::findOrFail($id);

        if (auth()->check() && auth()?->user()?->cannot('delete', $file)) return new JsonResponse([
            'message' => 'No access'
        ], 403);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
