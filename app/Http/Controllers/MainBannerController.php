<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainBanner\IndexMainBannerRequest;
use App\Http\Requests\MainBanner\UpdateMainBannerRequest;
use App\Models\MainBanner;
use App\Utils\QueryString;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainBannerController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/main-banner",
     *     tags={"MainBanner"},
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="image",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MainBannerSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexMainBannerRequest $request)
    {
        $main_banner = MainBanner::with(QueryString::convertToArray($request->extends))
            ->get();

        return new JsonResponse([
            'data' => $main_banner
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/main-banner",
     *     tags={"MainBanner"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="images",
     *                      description="Id файлов с типом картинки",
     *                      type="number",
     *                      example="1,2,3"
     *                  ),
     *                 example={
     *                     "images": "1,2,3"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MainBannerSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The images field is required."),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="images", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The images field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(UpdateMainBannerRequest $request)
    {
        MainBanner::truncate();

        foreach (QueryString::convertToArray($request->images) as $id) {
            MainBanner::create([
                'image_id' => $id
            ]);
        }

        $main_banner = MainBanner::with(QueryString::convertToArray($request->extends))
            ->get();

        return new JsonResponse([
            'data' => $main_banner
        ]);
    }
}
