<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\RecruitmentFlat\StoreRecruitmentFlatRequest;
use App\Http\Requests\RecruitmentFlat\UpdateRecruitmentFlatRequest;
use App\Models\Recruitment;
use App\Models\RecruitmentFlat;
use App\Policies\RecruitmentFlatPolicy;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecruitmentFlatController extends Controller
{
    private static function getWhere(): array
    {
        $where = [];

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/recruitment-flat",
     *     tags={"RecruitmentFlat"},
     *      @OA\Parameter(
     *          name="filter",
     *          description="Page",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[recruitment_id]":null,
     *                 "filter[flat_id]":null,
     *                 "filter[created_at]":null,
     *                 "filter[updated_at]":null,
     *               }
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Page",
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="Count",
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          description="Sorting",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ), 
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="recruitment,flat",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RecruitmentFlatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new RecruitmentFlat, $this::getWhere())
        );
    }

    /**
     * Post
     * @OA\Post (
     *     path="/api/recruitment-flat",
     *     tags={"RecruitmentFlat"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"recruitment_id", "flat_id"},
     *                      @OA\Property(
     *                          property="recruitment_id",
     *                          type="string",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="flat_id",
     *                          type="string",
     *                          example="1",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  ref="#/components/schemas/RecruitmentFlatSchema"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The recruitment_id field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="recruitment_id", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The recruitment_id is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="flat_id", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The flat_id field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StoreRecruitmentFlatRequest $request)
    {
        if (!RecruitmentFlatPolicy::create($request->recruitment_id)) return AccessUtil::errorMessage();

        $data = RecruitmentFlat::create(
            $request->validated()
        );

        return new JsonResponse([
            'data' => $data
        ]);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/recruitment-flat/{id}",
     *     tags={"RecruitmentFlat"},
     *      @OA\Parameter( 
     *          name="id",
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
     *          example="recruitment,flat",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RecruitmentFlatSchema"
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
    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new RecruitmentFlat, $id, $this::getWhere())
        ]);
    }

    /**
     * Update
     * @OA\Patch (
     *     path="/api/recruitment-flat/{id}",
     *     tags={"RecruitmentFlat"},
     *     security={{"bearer_token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="comment",
     *                          type="string",
     *                          example="Моя очень хорошая квартира"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RecruitmentFlatSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Comment max lenght 255"),
     *          )
     *      )
     * )
     */
    public function update(UpdateRecruitmentFlatRequest $request, int $id) {
        $data = RecruitmentFlat::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Filter::one($request, new RecruitmentFlat, $id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/recruitment-flat/{id}",
     *     tags={"RecruitmentFlat"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
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
        $recruitment_flat = RecruitmentFlat::findOrFail($id);

        if (AccessUtil::cannot('delete', $recruitment_flat)) return AccessUtil::errorMessage();

        RecruitmentFlat::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
