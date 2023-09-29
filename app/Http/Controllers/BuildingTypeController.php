<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuildingType\IndexBuildingTypeRequest;
use App\Models\BuildingType;
use App\Utils\QueryString;
use App\Utils\FilterRequestUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingTypeController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/building-type",
     *     tags={"BuildingType"},
     *     @OA\Parameter( 
     *          name="filterLIKE[name]",
     *          description="name",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter(
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
     *                  ref="#/components/schemas/BuildingTypeSchema"
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
    public function index(IndexBuildingTypeRequest $request)
    {
        $data_init = BuildingType::with(QueryString::convertToArray($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));
        $data_init = FilterRequestUtil::has($request->filterHas, $data_init);

        $data = $data_init->paginate($request->limit ?? 20);

        return new JsonResource(
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BuildingType $buildingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BuildingType $buildingType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BuildingType $buildingType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BuildingType $buildingType)
    {
        //
    }
}
