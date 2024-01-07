<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Application\DestroyApplicationRequest;
use App\Models\Application;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['id', '=', 'null'];
        }

        return $where;
    }

    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new Application, [], $this::getWhere())
        );
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
        $data = Application::create($request->validated());

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    public function update(UpdateApplicationRequest $request, int $id)
    {
        $data = Application::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Application::find($id)
        ]);
    }

    public function destroy(DestroyApplicationRequest $request, int $id)
    {
        $deleted = Application::destroy($id);

        if (!$deleted) return new JsonResponse([
            'message' => 'Not deleted',
            404
        ]);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
