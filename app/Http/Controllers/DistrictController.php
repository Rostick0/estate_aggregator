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
     *          name="filterEQ[country_id]",
     *          description="country_id",
     *          in="query",
     *          example="Москва",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filterLIKE[name]",
     *          description="name",
     *          in="query",
     *          example="5",
     *          @OA\Schema(
     *              type="number"
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
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"posts", "region"},
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
