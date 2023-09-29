<?php

namespace App\Http\Controllers;

use App\Http\Requests\Region\IndexRegionRequest;
use App\Models\Region;
use App\Http\Requests\Region\StoreRegionRequest;
use App\Http\Requests\Region\UpdateRegionRequest;
use App\Utils\QueryString;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/region",
     *     tags={"Region"},
     *     @OA\Parameter(
     *          name="filterLIKE[name]",
     *          description="name",
     *          in="query",
     *          example="Си",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filterEQ[country_id]",
     *          description="country_id",
     *          in="query",
     *          example="721",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
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
     *          example="30",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="country,districts",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RegionSchema"
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
    public function index(IndexRegionRequest $request)
    {
        $data_init = Region::with(QueryString::convertToArray($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));
        $data_init = OrderByUtil::set($request->sort, $data_init);

        $data = $data_init->paginate($request->limit ?? 50);

        return new JsonResponse(
            $data
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
