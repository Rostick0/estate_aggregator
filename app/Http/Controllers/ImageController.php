<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\ShowImageRequest;
use App\Models\Image;
use App\Http\Requests\Image\StoreImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageController extends Controller
{
    /**
     * Store
     * @OA\Post (
     *     path="/api/image",
     *     tags={"Image"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"file"},
     *                      @OA\Property(
     *                          property="file",
     *                          type="file",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ImageSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The file field is required."),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="The", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The The field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StoreImageRequest $request): JsonResponse
    {
        $image = $request->file('image');

        $extension = $image->getClientOriginalExtension();
        $random_name = 'upload/image/' . random_int(1000, 9999) . time() . '.' . $extension;
        [$width, $height] = getimagesize($image);

        $image->storeAs('public/' . $random_name);

        $data = Image::create([
            'name' =>  $image->getClientOriginalName(),
            'width' => $width,
            'height' => $height,
            'path' => url('') . '/storage/' . $random_name,
            'type' => $image->getClientMimeType(),
            'user_id' => auth()->id(),
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/image/{id}",
     *     tags={"Image"},
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
     *                  ref="#/components/schemas/ImageSchema"
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
    public function show(ShowImageRequest $request, int $id)
    {
        $img = Storage::get(
            Image::findOrFail($id)->path
        );

        $image = ImageManagerStatic::make($img);

        $image->resize($request?->w ?? $image->width(), $request->h ?? $image->height());

        return $image->response();
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/image/{id}",
     *     tags={"Image"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Image id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Deleted"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Access error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="No access"),
     *                 ),
     *          )
     *      )
     * )
     */
    public function destroy(int $id)
    {
        $image = Image::findOrFail($id);

        if (!auth()->check() || auth()?->user()?->cannot('delete', $image)) return new JsonResponse([
            'message' => 'No access'
        ], 403);

        Image::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
