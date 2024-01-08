<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use App\Utils\AccessUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $request_only = [
        'banner',
        'is_reliable',
    ];

    private static function extendsMutation($data, $request)
    {
        User::where('company_id', $data->id)
            ->where('role', 'realtor')
            ->update([
                'company_id' => null
            ]);
        if ($request->has('staffs')) {
            User::whereIn('id', QueryString::convertToArray($request->staffs))
                ->where('role', 'realtor')
                ->update([
                    'company_id' => $data->id
                ]);
        }
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/company",
     *     tags={"Company"},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[banner]":null,
     *                 "filter[is_reliable]":null,
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
     *          example="owner,staffs",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CompanySchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new Company)
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/company/{id}",
     *     tags={"Company"},
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
            'data' => Filter::one($request, new Company, $id)
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/company/{id}",
     *     tags={"Company"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
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
     *                          property="banner",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="is_reliable",
     *                          type="boolean",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="staffs",
     *                          type="string",
     *                          example="10,11,12"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/CompanySchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Access error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="No access"),
     *          )
     *      )
     * )
     */
    public function update(UpdateCompanyRequest $request, int $id)
    {
        $data = Company::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => Filter::one($request, new Company, $id)
        ]);
    }
}
