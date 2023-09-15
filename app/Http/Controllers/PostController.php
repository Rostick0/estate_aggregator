<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\IndexPostRequest;
use App\Http\Requests\Post\ShowPostRequest;
use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Image;
use App\Utils\ImageDBUtil;
use App\Utils\ImageUtil;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/post",
     *     tags={"Post"},
     *     @OA\Parameter( 
     *          name="title",
     *          description="Title post",
     *          in="query",
     *          example="Сайт",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="district_id",
     *          description="District id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="rubric_id",
     *          description="Rubric id",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          description="Page",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="Limit data",
     *          in="query",
     *          example="20",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"images", "main_image", "user", "district", "rubric"},
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
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
    public function index(IndexPostRequest $request)
    {
        $post_init = Post::with($request->extends ?? [])->orderByDesc('id');

        if ($request->title) $post_init->whereLike('title', $request->title);
        if ($request->district_id) $post_init->where('district_id', $request->district_id);
        if ($request->rubric_id) $post_init->where('rubric_id', $request->rubric_id);
       
        if (!$post_init->count()) return abort(404, 'Not found');

        $post = $post_init->paginate($request->limit ?? 20);

        return new JsonResponse(
            $post
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/post",
     *     tags={"Post"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"title", "content", "district_id", "rubric_id", "source"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Сайт",
     *                      ),
     *                      @OA\Property(
     *                          property="content",
     *                          type="string",
     *                          example="Хорошо делайте, плохо не делайте"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="3"
     *                      ),
     *                      @OA\Property(
     *                          property="rubric_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="source",
     *                          type="number",
     *                          example="<a href>сайт</a>"
     *                      ),
     *                      @OA\Property(
     *                          property="main_image",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="title", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The title title is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="content", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The content field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="district_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The district_id field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="rubric_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The rubric_id field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="source", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The source field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->only(
            'title',
            'content',
            'district_id',
            'rubric_id',
            'source'
        ));

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $post, 'post');
        if ($request->hasFile('main_image')) {
            $post->update(
                'main_image_id',
                ImageDBUtil::create($request->file('main_image'), $post, 'post')
            );
        }

        return new JsonResponse(
            [
                'data' => Post::find($post->id)
            ],
            201
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/post/{id}",
     *     tags={"Post"},
     *      @OA\Parameter( 
     *          name="id",
     *          description="Id",
     *          in="query",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="extends[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"images", "main_image", "user", "district", "rubric"},
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
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
    public function show(ShowPostRequest $request, int $id)
    {
        $post = Post::with($request->extends ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $post
            ],
        );
    }

    /**
     * Update
     * @OA\Post (
     *     path="/api/post/{id}",
     *     tags={"Post"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"title", "content", "district_id", "rubric_id", "source", "_method"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Сайт",
     *                      ),
     *                      @OA\Property(
     *                          property="content",
     *                          type="string",
     *                          example="Хорошо делайте, плохо не делайте"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="3"
     *                      ),
     *                      @OA\Property(
     *                          property="rubric_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="source",
     *                          type="number",
     *                          example="<a href>сайт</a>"
     *                      ),
     *                      @OA\Property(
     *                          property="main_image",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="images_delete[]",
     *                          type="number",
     *                      ),
     *                      @OA\Property(
     *                          property="_method",
     *                          type="string",
     *                          example="PUT"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="title", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The title title is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="content", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The content field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="district_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The district_id field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="rubric_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The rubric_id field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="source", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The source field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function update(UpdatePostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);

        if (auth()?->user()?->cannot('update', $post)) return abort(403, 'No access');

        $post->update($request->only(
            'title',
            'content',
            'district_id',
            'rubric_id',
            'source'
        ));

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $post, 'post');
        if ($request->hasFile('main_image')) {
            $post->update(
                'main_image_id',
                ImageDBUtil::create($request->file('main_image'), $post, 'post')
            );
        }

        if (!empty($request->images_delete)) ImageDBUtil::deleteImage($request->images_delete, $id, 'post');

        return new JsonResponse(
            [
                'data' => Post::find($id)
            ],
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/post/{id}",
     *     tags={"Post"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Post id",
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
     *              @OA\Property(property="message", type="string", example="Post 1 deleted"),
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
        $post = Post::findOrFail($id);
        
        if (auth()->check() && auth()?->user()?->cannot('delete', $post)) return abort(403, 'No access');


        $delete_image_ids = collect($post->images())->map(function ($item) {
            return $item->id;
        });

        ImageDBUtil::deleteImage([...$delete_image_ids], $id, 'post');
        Post::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ],
            204
        );
    }
}
