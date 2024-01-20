<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\SiteSeo\IndexSiteSeoRequest;
use App\Models\SiteSeo;
use App\Http\Requests\SiteSeo\StoreSiteSeoRequest;
use App\Http\Requests\SiteSeo\UpdateSiteSeoRequest;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSeoController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/site-seo",
     *     tags={"SiteSeo"},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[title]":null,
     *                 "filter[text]":null,
     *                 "filter[deleted_at]":null,
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
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/SiteSeoSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new SiteSeo)
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/site-seo",
     *     tags={"SiteSeo"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Я хочу Купить",
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string",
     *                          example="Если вы хотите купить недвижимость"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/SiteSeoSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required when none of images, files are present"),
     *          )
     *      )
     * )
     */
    public function store(StoreSiteSeoRequest $request)
    {
        $data = SiteSeo::create($request->validated());

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/site-seo/{id}",
     *     tags={"SiteSeo"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          example="1",
     *          in="path",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/SiteSeoSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new SiteSeo, $id)
        ]);
    }

    /**
     * Update
     * @OA\Patch (
     *     path="/api/site-seo/{id}",
     *     tags={"SiteSeo"},
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
     *                          property="title",
     *                          type="string",
     *                          example="Заголовок",
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string",
     *                          example="Текст"
     *                      ),
     *                      @OA\Property(
     *                          property="key",
     *                          type="string",
     *                          example="Ключ"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/SiteSeoSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required when none of images, files are present"),
     *          )
     *      )
     * )
     */
    public function update(UpdateSiteSeoRequest $request, int $id)
    {
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Filter::one($request, new SiteSeo, $id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/site-seo/{id}",
     *     tags={"SiteSeo"},
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
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('delete', $data)) return AccessUtil::errorMessage();

        SiteSeo::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }

    /**
     * Restore
     * @OA\Patch (
     *     path="/api/site-seo/{id}/restore",
     *     tags={"SiteSeo"},
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
    public function restore(int $id)
    {
        $data = SiteSeo::onlyTrashed()->findOrFail($id);

        if (AccessUtil::cannot('restore', $data)) return AccessUtil::errorMessage();

        $data->restore();

        return new JsonResponse([
            'message' => 'Restored'
        ]);
    }

    /**
     * forceDelete
     * @OA\Delete (
     *     path="/api/site-seo/{id}/force-delete",
     *     tags={"SiteSeo"},
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
    public function forceDelete(int $id)
    {
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('forceDelete', $data)) return AccessUtil::errorMessage();

        $data->forceDelete();

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
