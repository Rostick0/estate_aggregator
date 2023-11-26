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
                    'company' => $data->id
                ]);
        }
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

        return new JsonResponse([
            'data' => Filter::one($request, new Company, $id)
        ]);
    }
}
