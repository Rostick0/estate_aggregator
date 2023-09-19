<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationRequest;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/application",
     *     tags={"Application"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"name", "phone", "text", "messager_type"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                     @OA\Property(
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
     *                     "name":"Олег",
     *                     "email":"john@test.com",
     *                     "phone":"+799999",
     *                     "text": "Мне понравилась одна квартира, хотел бы ...",
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
     *                  ref="#/components/schemas/ApplicationSchema"
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
    public function store(StoreApplicationRequest $request)
    {
        $data = Application::create($request);

        return new JsonResponse(
            [
                'data' => $data
            ],
            201
        );
    }

    public function show(Application $application)
    {
        //
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        //
    }

    public function destroy(Application $application)
    {
        //
    }
}
