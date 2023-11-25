<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Alert\StoreAlertRequest;
use App\Http\Requests\Alert\UpdateAlertRequest;
use App\Models\Alert;
use App\Utils\AccessUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    private $request_only = [
        'title',
        'description',
        'country_id',
        'role',
        'type',
    ];

    private static function extendsMutation($data, $request)
    {
        $data->images()->delete();
        if ($request->has('images')) {
            $images = array_map(function ($image_id) {
                return ['image_id' => $image_id];
            }, QueryString::convertToArray($request->images));

            $data->images()->createMany($images);
        }
    }

    private static function getWhere()
    {
        return [];
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/alert",
     *     tags={"Alert"},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[title]":null,
     *                 "filter[description]":null,
     *                 "filter[country_id]":null,
     *                 "filter[role]":null,
     *                 "filter[type]":null,
     *                 "filter[status]":null,
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
     *          example="country,images,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new Alert, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/alert",
     *     tags={"Alert"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"title"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Заголовок",
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                          example="Описание описания"
     *                      ), @OA\Property(
     *                          property="country_id",
     *                          type="number",
     *                          example="5"
     *                      ),
     *                      @OA\Property(
     *                          property="role",
     *                          type="enum: client,realtor,agency,builder",
     *                          example="3000"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="number",
     *                          example="null"
     *                      ),
     *                      @OA\Property(
     *                          property="images",
     *                          type="string",
     *                          example="1,2"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertSchema"
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
     *                          example="The title field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="country_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The country_id not exsisted.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StoreAlertRequest $request)
    {
        $data = Alert::create([
            ...$request->only($this->request_only),
            'user_id' => auth()->id()
        ]);

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/alert/{id}",
     *     tags={"Alert"},
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
     *          example="country,images,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertSchema"
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
            'data' => Filter::one($request, new Alert, $id, $this::getWhere())
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/alert/{id}",
     *     tags={"Alert"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Alert id",
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
     *                      required={"title"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Заголовок",
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                          example="Описание описания"
     *                      ), @OA\Property(
     *                          property="country_id",
     *                          type="number",
     *                          example="5"
     *                      ),
     *                      @OA\Property(
     *                          property="role",
     *                          type="enum: client,realtor,agency,builder",
     *                          example="3000"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="number",
     *                          example="null"
     *                      ),
     *                      @OA\Property(
     *                          property="images",
     *                          type="string",
     *                          example="1,2"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/AlertSchema"
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
     *                          example="The title field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="country_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The country_id not exsisted.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function update(UpdateAlertRequest $request, int $id)
    {
        $data = Alert::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => Filter::one($request, new Alert, $id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/alert/{id}",
     *     tags={"Alert"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Alert id",
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
        $partner = Alert::findOrFail($id);

        if (AccessUtil::cannot('delete', $partner)) return AccessUtil::errorMessage();

        Alert::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
