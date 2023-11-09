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
    public function index()
    {
    }

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
     * Display the specified resource.
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
     * Remove the specified resource from storage.
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
