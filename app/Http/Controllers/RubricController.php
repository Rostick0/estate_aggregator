<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rubric\DestroyRubricRequest;
use App\Http\Requests\Rubric\IndexRubricRequest;
use App\Http\Requests\Rubric\ShowRubricRequest;
use App\Http\Requests\Rubric\StoreRubricRequest;
use App\Http\Requests\Rubric\UpdateRubricRequest;
use App\Models\Rubric;
use App\Utils\ExplodeExtends;
use App\Utils\FilterRequestUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function index(IndexRubricRequest $request)
    {
        $data_init = Rubric::with(ExplodeExtends::run($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));

        $data = $data_init->paginate($request->limit ?? 20);

        return new JsonResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRubricRequest $request)
    {
        $data = Rubric::create($request->validated());

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
    public function show(ShowRubricRequest $request, int $id)
    {
        $data = Rubric::findOrFail($id);

        return new JsonResponse(
            [
                'data' => $data
            ],
            201
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRubricRequest $request, int $id)
    {
        $data = Rubric::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse(
            [
                'data' => Rubric::find($id)
            ],
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRubricRequest $request, int $id)
    {
        $deleted = Rubric::destroy($id);

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
