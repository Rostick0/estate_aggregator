<?php

namespace App\Http\Controllers;

use App\Http\Requests\District\DestroyDistrictRequest;
use App\Http\Requests\District\IndexDistrictRequest;
use App\Http\Requests\District\ShowDistrictRequest;
use App\Models\District;
use App\Http\Requests\District\StoreDistrictRequest;
use App\Http\Requests\District\UpdateDistrictRequest;
use App\Utils\ExplodeExtends;
use App\Utils\FilterRequestUtil;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/district",
     *     tags={"District"},
     *     @OA\Parameter(
     *          name="filterEQ[region_id]",
     *          description="region_id",
     *          in="query",
     *          example="5",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filterLIKE[name]",
     *          description="name",
     *          in="query",
     *          example="Москва",
     *          @OA\Schema(
     *          format="textarea",
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
     *          example="posts,region",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/DistrictSchema"
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
    public function index(IndexDistrictRequest $request)
    {
        $data_init = District::with(ExplodeExtends::run($request->extends))
            ->orderBy('name');

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));

        $data = $data_init->paginate($request->limit ?? 50);

        return new JsonResponse(
            $data
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/district",
     *     tags={"District"},
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
     *                      @OA\Property(
     *                          property="importance",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="region_id",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Кахан",
     *                     "importance": 10,
     *                     "region_id":45
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/DistrictSchema"
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
    public function store(StoreDistrictRequest $request)
    {
        $data = District::create($request->validated());

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
     *     path="/api/district/{id}",
     *     tags={"District"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          in="path",
     *          example="523",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="posts,region",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/DistrictSchema"
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
    public function show(ShowDistrictRequest $request, int $id)
    {
        $data = District::with(ExplodeExtends::run($request->extends))->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $data
            ]
        );
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/district/{id}",
     *     tags={"District"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id",
     *          in="path",
     *          example="523",
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
     *                      @OA\Property(
     *                          property="importance",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="region_id",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Кахан",
     *                     "importance": 10,
     *                     "region_id":45
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/DistrictSchema"
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
    public function update(UpdateDistrictRequest $request, int $id)
    {
        $data = District::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse(
            [
                'data' => District::find($id)
            ],
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/district/{id}",
     *     tags={"District"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="District id",
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
    public function destroy(DestroyDistrictRequest $requst, int $id)
    {
        $deleted = District::destroy($id);

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
