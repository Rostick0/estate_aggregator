<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\IndexPropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/property",
     *     tags={"Property"},
     *      @OA\Parameter(
     *          name="extends[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"property_values"},
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PropertySchema"
     *              ),
     *          )
     *      ),
     */
    public function index(IndexPropertyRequest $request)
    {
        $data = Property::with($request->extends ?? [])->get();
    
        return new JsonResource(
            [
                'data' => $data
            ]
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
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        //
    }
}
