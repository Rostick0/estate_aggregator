<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/rubric",
     *     tags={"Rubric"},
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RubricSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index()
    {
        return new JsonResource(
            [
                'data' => Rubric::all()
            ]
        );
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
    public function show(Rubric $rubric)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rubric $rubric)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubric $rubric)
    {
        //
    }
}
