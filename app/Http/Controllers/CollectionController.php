<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Models\Collection;
use App\Http\Requests\Collection\DestroyCollectionRequest;
use App\Http\Requests\Collection\IndexCollectionRequest;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;

class CollectionController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/collection",
     *     tags={"Collection"},
     *      @OA\Parameter(
     *          name="filterEQ[type]",
     *          description="type, value",
     *          in="query",
     *          example="коллекция",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
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
     *          example="30",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CollectionSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexCollectionRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Collection)
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/collection",
     *     tags={"Collection"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="value",
     *                      description="Значение",
     *                      type="number",
     *                      example="Аренда"
     *                  ),                 
     *                  @OA\Property(
     *                      property="type",
     *                      description="Название",
     *                      type="number",
     *                      example="Тип продажи"
     *                  ),
     *                 example={
     *                     "value":"Аренда",
     *                     "collection_name":"test",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CollectionSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required."),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreCollectionRequest $request)
    {
        $data = Collection::create($request->validated());

        return new JsonResponse(
            [
                'data' => $data
            ],
            201
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/collection/{id}",
     *     tags={"Collection"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CollectionSchema"
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
    public function show(int $id)
    {
        $data = Collection::findOrFail($id);

        return new JsonResponse(
            [
                'data' => $data
            ],
            201
        );
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/collection/{id}",
     *     tags={"Collection"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id",
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="collection_name",
     *                      description="Название",
     *                      type="number",
     *                      example="Тип продажи"
     *                  ),
     *                  @OA\Property(
     *                      property="value",
     *                      description="Значение",
     *                      type="number",
     *                      example="Аренда"
     *                  ),
     *                 example={
     *                     "collection_name":"Тип продажи",
     *                     "value":"Аренда"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CollectionSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required."),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(UpdateCollectionRequest $request, int $id)
    {
        $data = Collection::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse(
            [
                'data' => Collection::find($id)
            ],
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/collection/{id}",
     *     tags={"Collection"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Collection id",
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
    public function destroy(DestroyCollectionRequest $request, int $id)
    {
        $deleted = Collection::destroy($id);

        if (!$deleted) return new JsonResponse([
            'message' => 'Not deleted',
            404
        ]);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ]
        );
    }
}
