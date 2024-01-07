<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\ApplicationFlat\DestroyApplicationFlatRequest;
use App\Models\ApplicationFlat;
use App\Http\Requests\ApplicationFlat\StoreApplicationFlatRequest;
use App\Http\Requests\ApplicationFlat\UpdateApplicationFlatRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationFlatController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['contact_id', '=', auth()?->id(), 'flat'];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/application-flat",
     *     tags={"ApplicationFlat"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[flat_id]":null,
     *                 "filter[is_information]":null,
     *                 "filter[is_viewing]":null,
     *                 "filter[name]":null,
     *                 "filter[phone]":null,
     *                 "filter[email]":null,
     *                 "filter[text]":null,
     *                 "filter[messager_type]":null,
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
     *          example="flat",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationFlatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new ApplicationFlat, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/application-flat",
     *     tags={"ApplicationFlat"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      required={"name", "phone", "text", "messager_type"},
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="messager_type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="is_agree",
     *                          type="boolean"
     *                      ),
     *                 ),
     *                 example={
     *                     "flat_id": 1,
     *                     "is_information": 0,
     *                     "is_viewing": 0,
     *                     "name":"Олег",
     *                     "email":"john@test.com",
     *                     "phone":"+799999",
     *                     "text": "Мне понравилась ваша квартира, хотел бы ...",
     *                     "messager_type": "telegram",
     *                     "is_agree": true
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationFlatSchema"
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The email field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="phone", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The phone field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreApplicationFlatRequest $request)
    {
        $data = ApplicationFlat::create($request->validated());

        return new JsonResponse([
            'data' => $data,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationFlatRequest $request, int $id)
    {
        $data = ApplicationFlat::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => ApplicationFlat::find($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyApplicationFlatRequest $request, int $id)
    {
        $deleted = ApplicationFlat::destroy($id);

        if (!$deleted) return new JsonResponse([
            'message' => 'Not deleted',
            404
        ]);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
