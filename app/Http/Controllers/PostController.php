<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\IndexPostRequest;
use App\Http\Requests\Post\ShowPostRequest;
use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Image;
use App\Utils\ExplodeExtends;
use App\Utils\FilterRequestUtil;
use App\Utils\ImageDBUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/post",
     *     tags={"Post"},
     *     @OA\Parameter( 
     *          name="filterLIKE[title]",
     *          description="title",
     *          in="query",
     *          example="Сайт",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="filterEQ[rubric_id]",
     *          description="district_id, rubric_id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="sort",
     *          description="Сортировка по параметру",
     *          in="query",
     *          example="id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
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
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="images,main_image,user,district,rubric",
     *          @OA\Schema(
     *              type="string",
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
        $data_init = Post::with(ExplodeExtends::run($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));
        $data_init = OrderByUtil::set($request->sort, $data_init);

        $data = $data_init->paginate($request->limit ?? 20);

        return new JsonResponse(
            $data
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/post",
     *     tags={"Post"},
     *     security={{"bearer_token": {}}},
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
     *                          example="1702"
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
     *                          type="file",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
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
        $post = Post::create([
            ...$request->only(
                'title',
                'content',
                'district_id',
                'rubric_id',
                'source'
            ),
            'user_id' => auth()->id()
        ]);

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $post->id, 'post');
        if ($request->hasFile('main_image')) {
            $post->update(
                [
                    'main_image_id' => ImageDBUtil::create($request->file('main_image'), $post->id, 'post')
                ]
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
     *          in="path",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="images,main_image,user,district,rubric",
     *          @OA\Schema(
     *              type="string",
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
        $post = Post::with(ExplodeExtends::run($request->extends))->findOrFail($id);

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
     *     security={{"bearer_token": {}}},
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
     *                          example="1702"
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
     *                          type="file",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
     *                          type="file",
     *                      ),
     *                      @OA\Property(
     *                          property="images_delete",
     *                          description="Пример: 1,2,3",
     *                          type="string",
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

        if (auth()?->user()?->cannot('update', $post)) return new JsonResponse(
            [
                'message' => 'No access'
            ],
            404
        );

        $post->update($request->only(
            'title',
            'content',
            'district_id',
            'rubric_id',
            'source'
        ));

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $post->id, 'post');
        if ($request->hasFile('main_image')) {
            $post->update(
                [
                    'main_image_id' => ImageDBUtil::create($request->file('main_image'), $post->id, 'post')
                ]
            );
        }

        if (!empty($request->images_delete)) ImageDBUtil::deleteImage(explode(',', $request->images_delete), $id, 'post');

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
     *     security={{"bearer_token": {}}},
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

        if (auth()->check() && auth()?->user()?->cannot('delete', $post)) return new JsonResponse(
            [
                'message' => 'No access'
            ],
            404
        );

        $delete_image_ids = collect($post->images()->get())->map(function ($item) {
            return $item->id;
        });

        ImageDBUtil::deleteImage([...$delete_image_ids], $id, 'post');
        Post::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ]
        );
    }
}
