<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\ApplicationCompany\StoreApplicationCompanyRequest;
use App\Http\Requests\ApplicationCompany\UpdateApplicationCompanyRequest;
use App\Models\ApplicationCompany;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationCompanyController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin' && array_search(auth()?->user()->role, ['agency', 'builder'])) {
            $where[] = ['company_id', '=', auth()?->user()->company_id];
        } else {
            $where[] = ['user_id', '=', auth()?->id()];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/application-company",
     *     tags={"ApplicationCompany"},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[user_id]":null,
     *                 "filter[company_id]":null,
     *                 "filter[status]":null,
     *                 "filter[created_at]":null,
     *                 "filter[updated_at]":null,
     *               }
     *          )
     *      ),
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
     *          example="user,company",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationCompanySchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new ApplicationCompany, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/application-company",
     *     tags={"ApplicationCompany"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      required={"company_id"},
     *                      type="object",
     *                      @OA\Property(
     *                          property="company_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *                     "company_id": 1,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationCompanySchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The company_id field is required"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreApplicationCompanyRequest $request)
    {
        $data = ApplicationCompany::create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/application-company/{id}",
     *     tags={"ApplicationCompany"},
     *     @OA\Parameter(
     *          name="id",
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
     *          example="user,company",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationCompanySchema"
     *              ),
     *          ),
     *      ),
     * )
     */
    public function show(UpdateApplicationCompanyRequest $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new ApplicationCompany, $id, $this::getWhere())
        );
    }

    /**
     * Upadte
     * @OA\put (
     *     path="/api/application-company/{id}",
     *     security={{"bearer_token": {}}},
     *     tags={"ApplicationCompany"},
     *      @OA\Parameter(
     *          name="id",
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
     *                      required={"status"},
     *                      type="object",
     *                      @OA\Property(
     *                          property="status",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "status": 1,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationCompanySchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The status field is required"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(UpdateApplicationCompanyRequest $request, int $id)
    {
        $data = ApplicationCompany::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Filter::one($request, new ApplicationCompany, $id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/application-сompany/{id}",
     *     tags={"ApplicationCompany"},
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
        $data = ApplicationCompany::findOrFail($id);

        if (AccessUtil::cannot('delete', $data)) return AccessUtil::errorMessage();

        ApplicationCompany::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
