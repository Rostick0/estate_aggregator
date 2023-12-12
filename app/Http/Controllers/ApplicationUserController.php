<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Models\ApplicationUser;
use App\Http\Requests\ApplicationUser\StoreApplicationUserRequest;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationUserController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['id', '=', NULL];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/application-user",
     *     tags={"ApplicationUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[role]":null,
     *                 "filter[user_id]":null,
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
     *          example="user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new ApplicationUser, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/application-user",
     *     tags={"ApplicationUser"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *              @OA\Schema(
     *                 required={"role"},
     *                 @OA\Property(
     *                  property="role",
     *                  type="enum", enum={"client", "realtor", "agency", "builder"}
     *                 ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationUserSchema"
     *                  ),
     *              ),
     *          ),
     *      ),
     * )
     */
    public function store(StoreApplicationUserRequest $request)
    {
        $data = ApplicationUser::create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);

        return new JsonResponse([
            'data' => $data,
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/application-user/{id}",
     *     tags={"ApplicationUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Сортировка по параметру",
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
     *          example="user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new ApplicationUser, $id, $this::getWhere())
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/application-user/{id}",
     *     tags={"ApplicationUser"},
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
        $application_user = ApplicationUser::findOrFail($id);

        if (AccessUtil::cannot('delete', $application_user)) return AccessUtil::errorMessage();

        ApplicationUser::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
