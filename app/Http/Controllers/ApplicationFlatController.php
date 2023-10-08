<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFlat;
use App\Http\Requests\ApplicationFlat\StoreApplicationFlatRequest;
use App\Http\Requests\ApplicationFlat\UpdateApplicationFlatRequest;
use Illuminate\Http\JsonResponse;

class ApplicationFlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/application-flat",
     *     tags={"ApplicationFlat"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      required={"name", "phone", "text", "messager_type"},
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="messager_type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="is_agree",
     *                          type="boolean"
     *                      ),
     *                 ),
     *                 example={
     *                     "flat_id": 1,
     *                     "is_information": 0,
     *                     "is_viewing": 0,
     *                     "name":"Олег",
     *                     "email":"john@test.com",
     *                     "phone":"+799999",
     *                     "text": "Мне понравилась ваша квартира, хотел бы ...",
     *                     "messager_type": "telegram",
     *                     "is_agree": true
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ApplicationFlatSchema"
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The email field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="phone", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The phone field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreApplicationFlatRequest $request)
    {
        $data = ApplicationFlat::create($request->validated());

        return new JsonResponse([
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationFlat $applicationFlat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationFlatRequest $request, ApplicationFlat $applicationFlat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationFlat $applicationFlat)
    {
        //
    }
}
