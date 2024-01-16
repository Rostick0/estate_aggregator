<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\SiteInfo\IndexSiteInfoRequest;
use App\Models\SiteInfo;
use App\Http\Requests\SiteInfo\StoreSiteInfoRequest;
use App\Http\Requests\SiteInfo\UpdateSiteInfoRequest;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteInfoController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/site-info",
     *     tags={"SiteInfo"},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[title]":null,
     *                 "filter[text]":null,
     *                 "filter[key]":null,
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
     *                  ref="#/components/schemas/SiteInfoSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new SiteInfo)
        );
    }

    public function store(StoreSiteInfoRequest $request)
    {
        $data = SiteInfo::create($request->validated());

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new SiteInfo, $id)
        ]);
    }

    public function update(UpdateSiteInfoRequest $request, int $id)
    {
        $data = SiteInfo::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Filter::one($request, new SiteInfo, $id)
        ]);
    }

    public function destroy(int $id)
    {
        $data = SiteInfo::findOrFail($id);

        if (AccessUtil::cannot('delete', $data)) return AccessUtil::errorMessage();

        SiteInfo::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }

    public function restore(int $id)
    {
        $data = SiteInfo::onlyTrashed()->findOrFail($id);

        if (AccessUtil::cannot('restore', $data)) return AccessUtil::errorMessage();

        $data->restore();

        return new JsonResponse([
            'message' => 'Restored'
        ]);
    }

    public function forceDelete(int $id)
    {
        $data = SiteInfo::findOrFail($id);

        if (AccessUtil::cannot('forceDelete', $data)) return AccessUtil::errorMessage();

        $data->forceDelete();

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
