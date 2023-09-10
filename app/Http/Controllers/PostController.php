<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\IndexPostRequest;
use App\Http\Requests\Post\ShowPostRequest;
use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPostRequest $request)
    {
        $post_init = Post::with($request->extends ?? []);

        if ($request->title) $post_init->whereLike('title', $request->title);
        if ($request->city_id) $post_init->where('city_id', $request->city_id);
        if ($request->rubric_id) $post_init->where('rubric_id', $request->rubric_id);

        $post = $post_init->paginate($request->limit ?? 20);

        return new JsonResponse(
            $post
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return new JsonResponse(
            [
                'data' => Post::find($post->id)
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowPostRequest $request, int $id)
    {
        $post = Post::with($request ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $post
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);

        if (auth()?->user()?->cannot('update', $post)) return abort(403, 'no auth');

        $post->update($request);

        return new JsonResponse(
            [
                'data' => Post::find($id)
            ],
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        if (auth()?->user()?->cannot('update', $post)) return abort(403, 'no auth');

        Post::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ],
            204
        );
    }
}
