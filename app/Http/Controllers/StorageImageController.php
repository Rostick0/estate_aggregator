<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\ShowImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class StorageImageController extends Controller
{
    public function show(ShowImageRequest $request, string $path)
    {
        $img = Storage::get('public/upload/image/' . $path);
        if (!$img) abort(404);

        $image = ImageManagerStatic::make($img);

        $image->resize($request?->w ?? $image->width(), $request->h ?? $image->height());

        return $image->response();
    }
}
