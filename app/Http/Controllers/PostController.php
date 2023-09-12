<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\IndexPostRequest;
use App\Http\Requests\Post\ShowPostRequest;
use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Image;
use App\Utils\ImageUtil;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private static function uploadImage($images, Post $post)
    {
        foreach ($images as $image) {
            $path = ImageUtil::upload($image);

            [$width, $height] = getimagesize($image);

            $post->images()->create([
                'name' => $image->getClientOriginalName(),
                'path' => $path,
                'type' => 'post',
                'width' => $width,
                'height' => $height,
            ]);
        }
    }

    private static function deleteImage(array $images_delete_ids, int $id)
    {
        $images = collect(Image::whereIn('id', $images_delete_ids)->where('type_id', $id)->get());

        $images->each(function ($item) {
            ImageUtil::delete($item->path);

            Image::destroy($item->id);
        });
    }


    public function index(IndexPostRequest $request)
    {
        $post_init = Post::with($request->extends ?? [])->orderByDesc('id');

        if ($request->title) $post_init->whereLike('title', $request->title);
        if ($request->city_id) $post_init->where('city_id', $request->city_id);
        if ($request->rubric_id) $post_init->where('rubric_id', $request->rubric_id);

        $post = $post_init->paginate($request->limit ?? 20);

        return new JsonResponse(
            $post
        );
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->only(
            'title',
            'content',
            'city_id',
            'rubric_id',
            'source'
        ));

        if ($request->hasFile('images')) PostController::uploadImage($request->file('images'), $post);

        return new JsonResponse(
            [
                'data' => Post::find($post->id)
            ],
            201
        );
    }

    public function show(ShowPostRequest $request, int $id)
    {
        $post = Post::with($request ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $post
            ],
        );
    }

    public function update(UpdatePostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);

        if (auth()?->user()?->cannot('update', $post)) return abort(403, 'no auth');

        $post->update($request);

        if ($request->hasFile('images')) PostController::uploadImage($request->file('images'), $post);

        if (!empty($request->images_delete)) PostController::deleteImage($request->images_delete, $id);

        return new JsonResponse(
            [
                'data' => Post::find($id)
            ],
        );
    }

    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        if (auth()->check() && auth()?->user()?->cannot('delete', $post)) return abort(403, 'no auth');


        $delete_image_ids = collect($post->images())->map(function ($item) {
            return $item->id;
        });

        $this::deleteImage([...$delete_image_ids], $id);
        Post::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ],
            204
        );
    }
}
