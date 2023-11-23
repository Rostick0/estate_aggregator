<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Models\ObjectFlat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObjectFlatController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/object-flat",
     *     tags={"ObjectFlat"},
     *      @OA\Parameter(
     *          name="filter",
     *          description="Page",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[name]":null,
     *                 "filter[type]":null,
     *                 "filter[created_at]":null,
     *                 "filter[updated_at]":null,
     *               }
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Page",
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="Count",
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          description="Sorting",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ObjectFlatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new ObjectFlat)
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show(int $id)
    {
        //
    }

    public function update(Request $request, int $id)
    {
        //
    }

    public function destroy(int $id)
    {
        //
    }
}
