<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\User\UpdatePasswordUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Utils\AccessUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $request_only = [
        'name',
        'email',
        'phone',
        'image_id',
        'country_id',
        'type_social',
        'about',
        'work_experience',
    ];

    private static function extendsMutation($data, $request)
    {
        $data->collection_relats()->delete();
        if ($request->has('collection_relats')) {
            $collection_relats = array_map(function ($collection_id) {
                return ['collection_id' => $collection_id];
            }, QueryString::convertToArray($request->collection_relats));

            $data->collection_relats()->createMany($collection_relats);
        }
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/user",
     *     tags={"User"},
     *      @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[name]":null,
     *                 "filter[email]":null,
     *                 "filter[phone]":null,
     *                 "filter[role]":null,
     *                 "filter[country_id]":null,
     *                 "filter[is_confirm]":null,
     *                 "filter[type_social]":null,
     *                 "filter[raiting_awe]":null,
     *                 "filter[raiting]":null,
     *                 "filter[work_experience]":null,
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
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="contacts,country,image,flat_owners,alert,collection_relats.collection",
     *          @OA\Schema(
     *              type="string",
     *          )
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
     * )
     */
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new User)
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/user/{id}",
     *     tags={"User"},
     *      @OA\Parameter( 
     *          name="id",
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
     *          example="contacts,country,image,flat_owners,alert,collection_relats.collection",
     *          @OA\Schema(
     *              type="string",
     *          )
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
            'data' => Filter::one($request, new User, $id)
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/user/{id}",
     *     tags={"User"},
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
     *                      required={"name", "phone"},
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
     *                          property="image",
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
     *                          property="collection_relats",
     *                          description="Коллекции связаные с пользователем",
     *                          type="string",
     *                          example="1,2"
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
     *                  @OA\Property(property="message", type="string", example="The name field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="phone", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The phone field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $data = User::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => Filter::one($request, new User, $id)
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/user-password",
     *     tags={"User"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"password", "new_password"},
     *                      @OA\Property(
     *                          property="password",
     *                          type="string",
     *                          example="aboba123",
     *                      ),
     *                      @OA\Property(
     *                          property="new_password",
     *                          type="number",
     *                          example="aboba1234"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object", example="Password changed")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The password field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="password", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The password is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="new_password", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The new_password field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function update_password(UpdatePasswordUserRequest $request)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return new JsonResponse([
                'message' => 'Неверный пароль'
            ], 400);
        }

        User::find(auth()->id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        return new JsonResponse([
            'message' => 'Пароль успешно изменён'
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/user/{id}",
     *     tags={"User"},
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
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Deleted"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Access error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="No access"),
     *                 ),
     *          )
     *      )
     * )
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if (AccessUtil::cannot('delete', $user)) return AccessUtil::errorMessage();

        User::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
