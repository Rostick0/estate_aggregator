<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Recruitment\StoreRecruitmentRequest;
use App\Http\Requests\Recruitment\UpdateRecruitmentRequest;
use App\Models\Recruitment;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecruitmentController extends Controller
{
    private $request_only = [
        'name',
    ];

    private static function getWhere()
    {
        return [];
    }

    /**
     * Index
     * @OA\Get (
     *     path="/api/recruitment",
     *     tags={"Recruitment"},
     *      @OA\Parameter(
     *          name="filter",
     *          description="Page",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[name]":null,
     *                 "filter[key]":null,
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
     *          example="recruitment_flats,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RecruitmentSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new Recruitment, $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/recruitment",
     *     tags={"Recruitment"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"name"},
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Подборка",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  ref="#/components/schemas/RecruitmentSchema"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required."),
     *          )
     *      )
     * )
     */
    public function store(StoreRecruitmentRequest $request)
    {
        $data = Recruitment::create([
            ...$request->only($this->request_only),
            'key' => Str::random(20),
            'user_id' => auth()->id()
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\Get (
     *     path="/api/recruitment/{id}",
     *     tags={"Recruitment"},
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
     *          example="recruitment_flats,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RecruitmentSchema"
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
            'data' => Filter::one($request, new Recruitment, $id, $this::getWhere())
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/recruitment/{id}",
     *     tags={"Recruitment"},
     *     security={{"bearer_token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
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
     *                      required={"name"},
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Подборка",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  ref="#/components/schemas/RecruitmentSchema"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required."),
     *          )
     *      )
     * )
     */
    public function update(UpdateRecruitmentRequest $request, int $id)
    {
        $data = Recruitment::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        return new JsonResponse([
            'data' => Filter::one($request, new Recruitment, $id, $this::getWhere())
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/recruitment/{id}",
     *     tags={"Recruitment"},
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
        $recruitment = Recruitment::findOrFail($id);

        if (AccessUtil::cannot('delete', $recruitment)) return AccessUtil::errorMessage();

        Recruitment::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
