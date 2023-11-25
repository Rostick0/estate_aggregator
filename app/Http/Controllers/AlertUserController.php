<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\AlertUserRequest\StoreAlertUserRequest;
use App\Models\Alert;
use App\Models\AlertUser;
use App\Models\User;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertUserController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()->user()->role !== 'admin') {
            $where[] = ['user_id', '=', auth()->id()];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/alert-user",
     *     tags={"AlertUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[alert_id]":null,
     *                 "filter[user_id]":null,
     *                 "filter[is_read]":null,
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
     *          example="alert,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new AlertUser, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/alert-user",
     *     tags={"AlertUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"alert_id"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                     @OA\Property(
     *                          property="alert_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="user_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="country_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="role",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="send_at",
     *                          type="datetime"
     *                      ),
     *                 ),
     *                 example={
     *                     "alert_id": "1",
     *                     "user_id": null,
     *                     "country_id": null,
     *                     "role": "cleint",
     *                     "send_at": null,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertUserSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The alert_id field is required"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreAlertUserRequest $request)
    {
        if ($request->user_id) {
            AlertUser::create([
                $request->only(['alert_id', 'user_id', 'send_at'])
            ]);
        } else {
            $alert = Alert::find($request->alert_id);
            $user = User::query();

            if ($alert->country_id) $user->where('country_id', $alert->country_id);
            if ($alert->role) $user->where('role', $alert->role);

            $user->alert()->create([
                'alert_id' => $request->alert_id,
                'send_at' => $request?->send_at ?? null
            ]);
        }

        return new JsonResponse([
            'data' => [
                'message' => 'Created'
            ]
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/alert-user/{id}",
     *     tags={"AlertUser"},
     *     security={{"bearer_token": {}}},
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="alert,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new AlertUser, $id, $this::getWhere())
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/alert-user/{id}",
     *     tags={"AlertUser"},
     *     security={{"bearer_token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"alert_id"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                     @OA\Property(
     *                          property="alert_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="user_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="country_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="role",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="send_at",
     *                          type="datetime"
     *                      ),
     *                 ),
     *                 example={
     *                     "alert_id": "1",
     *                     "user_id": null,
     *                     "country_id": null,
     *                     "role": "cleint",
     *                     "send_at": null,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertUserSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The alert_id field is required"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(Request $request, int $id)
    {
        $data = AlertUser::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only('is_read')
        );

        return new JsonResponse([
            'data' => Filter::one($request, new AlertUser, $id)
        ]);
    }

/**
     * Delete
     * @OA\Delete (
     *     path="/api/alert-user/{id}",
     *     tags={"AlertUser"},
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
        $partner = AlertUser::findOrFail($id);

        if (AccessUtil::cannot('delete', $partner)) return AccessUtil::errorMessage();

        AlertUser::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
