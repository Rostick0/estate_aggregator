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

    /**
     * Show
     * @OA\get (
     *     path="/api/file/{id}",
     *     tags={"File"},
     *     @OA\Parameter( 
     *          name="id",
     *          description="Id",
     *          in="path",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FileSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Not found"),
     *                  ),
     *          )
     *      )
     * )
     */
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
