<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\Company;
use App\Models\User;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private $request_only = [
        'name',
        'email',
        'phone',
        'image_id',
        'country_id',
        'type_social',
        'is_confirm',
        'role',
        'about',
        'work_experience',
        'company_id',
    ];

    /**
     * Update
     * @OA\Put (
     *     path="/api/admin/user/{id}",
     *     tags={"Admin/User"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="User id",
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
     *                      required={"is_confirm", "name", "phone"},
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Дмитрий",
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          example="myemail@gmail.com"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="number",
     *                          example="79299999999"
     *                      ),
     *                      @OA\Property(
     *                          property="image_id",
     *                          description="Добавление по id картинки, наример: 1",
     *                          type="string",
     *                      ),                     
     *                      @OA\Property(
     *                          property="country_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="type_social",
     *                          type="enum: whatsapp,viber,telegram",
     *                          example="whatsapp"
     *                      ),
     *                      @OA\Property(
     *                          property="is_confirm",
     *                          type="boolean",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="about",
     *                          type="string",
     *                          example="aboba",
     *                      ),
     *                      @OA\Property(
     *                          property="work_experience",
     *                          type="float",
     *                          example="1.5",
     *                      ),
     *                      @OA\Property(
     *                          property="company_id",
     *                          type="number",
     *                          example="1",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/UserSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required."),
     *          )
     *      )
     * )
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        $data = User::findOrFail($id);

        $data->update(
            $request->only($this->request_only)
        );

        if ($request->is_confirm && !$data->company_id && array_search($request->role, ['agency', 'builder']) !== false) {

            $company = $data->company()->firstOrCreate();
            $data->update([
                'company_id' => $company->id,
            ]);
        }

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

        return new JsonResponse([
            'data' => Filter::one($request, new User, $id)
        ]);
    }
}
