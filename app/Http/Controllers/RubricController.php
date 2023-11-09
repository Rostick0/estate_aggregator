<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Rubric\DestroyRubricRequest;
use App\Http\Requests\Rubric\IndexRubricRequest;
use App\Http\Requests\Rubric\ShowRubricRequest;
use App\Http\Requests\Rubric\StoreRubricRequest;
use App\Http\Requests\Rubric\UpdateRubricRequest;
use App\Models\Rubric;
use App\Utils\QueryString;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/rubric",
     *     tags={"Rubric"},
     *      @OA\Parameter(
     *          name="filterEQ[name]",
     *          description="name",
     *          in="query",
     *          example="721",
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
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="posts",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RubricSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexRubricRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Rubric)
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/rubric",
     *     tags={"Rubric"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"Продажа"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RubricSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="region_id", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The region_id field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreRubricRequest $request)
    {
        $data = Rubric::create($request->validated());

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
     *     path="/api/rubric/{id}",
     *     tags={"Rubric"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="posts",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RubricSchema"
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
    public function show(ShowRubricRequest $request, int $id)
    {
        $data = Rubric::findOrFail($id);

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
     *     path="/api/rubric/{id}",
     *     tags={"Rubric"},
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
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"Продажа",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RubricSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="region_id", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The region_id field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(UpdateRubricRequest $request, int $id)
    {
        $data = Rubric::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse(
            [
                'data' => Rubric::find($id)
            ],
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/rubric/{id}",
     *     tags={"Rubric"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Rubric id",
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
    public function destroy(DestroyRubricRequest $request, int $id)
    {
        $deleted = Rubric::destroy($id);

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
