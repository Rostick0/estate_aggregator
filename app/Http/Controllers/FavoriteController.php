<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Favorite\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private $request_only = [
        'flat_id',
    ];

    private static function getWhere()
    {
        $where = [];

        if (
            auth()->user()->role !== 'admin'
        ) {
            $where[] = ['user_id', '=', auth()->id()];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/favorite",
     *     tags={"Favorite"},
     *     @OA\Parameter( 
     *          name="filterEQ[flat_id]",
     *          description="flat_id, user_id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
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
     *          example="flat,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FavoriteSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(
            Filter::all($request, new Favorite, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/favorite",
     *     tags={"Favorite"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"flat_id"},
     *                      @OA\Property(
     *                          property="flat_id",
     *                          type="string",
     *                          example="1",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FavoriteSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="flat_id", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The flat_id field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $data = Favorite::firstOrCreate([
            ...$request->only($this->request_only),
            'user_id' => $request->user()->id
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/favorite/{id}",
     *     tags={"Favorite"},
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
     *          example="flat,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FavoriteSchema"
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
    public function show(Request $request, int $id): JsonResponse
    {
        return new JsonResponse([
            'data' => Filter::one($request, new Favorite, $id, $this::getWhere())
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/favorite/{id}",
     *     tags={"Favorite"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Favorite id",
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
    public function destroy(int $id): JsonResponse
    {
        $favorite = Favorite::where('flat_id', $id)->first();

        if (AccessUtil::cannot('delete', $favorite)) return AccessUtil::errorMessage();

        $favorite->delete();

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
