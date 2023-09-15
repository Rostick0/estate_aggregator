<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flat\IndexFlatRequest;
use App\Http\Requests\Flat\ShowFlatRequest;
use App\Models\Flat;
use App\Http\Requests\Flat\StoreFlatRequest;
use App\Http\Requests\Flat\UpdateFlatRequest;
use App\Utils\ImageDBUtil;
use Illuminate\Http\JsonResponse;

class FlatController extends Controller
{
    /**
     * Index
     * @OA\get (
     *     path="/api/flat",
     *     tags={"Flat"},
     *     @OA\Parameter( 
     *          name="object_id",
     *          description="Object id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="type_id",
     *          description="Покупка 1 или аренда 2",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="currency_id",
     *          description="Тип валюты",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="price",
     *          description="Price",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="country_id",
     *          description="Country id",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="search",
     *          description="Поиск по стране, городу, адресу",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter(
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
     *          name="extends[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"flat_properties", "object", "type", "country", "district", "currency", "square_land_unit", "building_type", "user", "images"},
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="flat_properties[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
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
    public function index(IndexFlatRequest $request)
    {
        $data_init = Flat::with($request->extends ?? []);

        if ($request->object_id) $data_init->where('object_id', $request->object_id);
        if ($request->type_id) $data_init->where('type_id', $request->type_id);
        if ($request->country_id) $data_init->where('country_id', $request->country_id);
        if ($request->district_id) $data_init->where('district_id', $request->district_id);

        if ($request->search) {
            $data_init->whereHas('countries', function ($query) use ($request) {
                $query->whereLike('name', $request->search);
            });

            $data_init->whereHas('districts', function ($query) use ($request) {
                $query->whereLike('name', $request->search);
            });

            $data_init->whereLike('address', $request->search);
        }

        $data = $data_init->paginate($request->limit ?? 20);

        return new JsonResponse(
            $data
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/flat",
     *     tags={"Flat"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"object_id", "type_id", "country_id", "district_id", "longitude", "latitude", "currency_id", "price"},
     *                      @OA\Property(
     *                          property="object_id",
     *                          type="number",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="type_id",
     *                          description="Продажа 1 или аренда 2",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="country_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="district",
     *                          type="string",
     *                          example="Город, которого нет в бд"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          example="Пушкина 1"
     *                      ),
     *                      @OA\Property(
     *                          property="longitude",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="latitude",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="currency_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="number",
     *                          example="10000000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_day",
     *                          type="number",
     *                          example="3000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_week",
     *                          type="number",
     *                          example="10000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_month",
     *                          type="number",
     *                          example="35000"
     *                      ),
     *                      @OA\Property(
     *                          property="not_show_price",
     *                          type="boolean",
     *                          example="0"
     *                      ),
     *                      @OA\Property(
     *                          property="rooms",
     *                          type="number",
     *                          example="2"
     *                      ),
     *                      @OA\Property(
     *                          property="bedrooms",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="bathrooms",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="square",
     *                          type="number",
     *                          example="40"
     *                      ),
     *                      @OA\Property(
     *                          property="square_land",
     *                          type="number",
     *                          example="40"
     *                      ),
     *                      @OA\Property(
     *                          property="square_land_unit",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="floor",
     *                          type="number",
     *                          example="10"
     *                      ),
     *                      @OA\Property(
     *                          property="total_floor",
     *                          type="number",
     *                          example="10"
     *                      ),
     *                      @OA\Property(
     *                          property="building_type",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="building_date",
     *                          type="string",
     *                          example="15.05.2012"
     *                      ),
     *                      @OA\Property(
     *                          property="specialtxt",
     *                          type="string",
     *                          example="Специальный текст, специально для сайта"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                          example="Описание, которые покорит сердца покупателя"
     *                      ),
     *                      @OA\Property(
     *                          property="filename",
     *                          type="string",
     *                          example="https://www.youtube.com/watch?v=4KZ2GeRWs1g"
     *                      ),
     *                      @OA\Property(
     *                          property="tour_link",
     *                          type="string",
     *                          example="https://www.youtube.com/watch?v=4KZ2GeRWs1g"
     *                      ),
     *                      @OA\Property(
     *                          property="propertie_values[]",
     *                          type="string",
     *                          format="number",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FlatSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="square", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The square field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="district_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The district_id field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function store(StoreFlatRequest $request)
    {
        $values = $request->only([
            'object_id',
            'type_id',
            'country_id',
            'district_id',
            'district',
            'address',
            'longitude',
            'latitude',
            'currency_id',
            'price',
            'price_day',
            'price_week',
            'price_month',
            'not_show_price',
            'rooms',
            'bedrooms',
            'bathrooms',
            'square',
            'square_land',
            'square_land_unit',
            'floor',
            'total_floor',
            'building_type',
            'building_date',
            'specialtxt',
            'description',
            'filename',
            'tour_link',
        ]);

        $flat = Flat::create([
            ...$values,
            'contact_id' => auth()->id()
        ]);

        FlatPropertyController::createProperites($request->propertie_values, $flat);
        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $flat, 'flat');

        return new JsonResponse(
            [
                'data' => Flat::find($flat->id)
            ],
            201
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     @OA\Parameter( 
     *          name="id",
     *          description="Id",
     *          in="query",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="extends[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *              example={"flat_properties", "object", "type", "country", "district", "currency", "square_land_unit", "building_type", "user", "images"},
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="flat_properties[]",
     *          description="Extends data",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(type="string"),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/PostSchema"
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
    public function show(ShowFlatRequest $request, int $id)
    {
        $flat = Flat::with($request->extends ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $flat
            ],
        );
    }

    /**
     * Update
     * @OA\Post (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"object_id", "type_id", "country_id", "district_id", "longitude", "latitude", "currency_id", "price"},
     *                      @OA\Property(
     *                          property="object_id",
     *                          type="number",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="type_id",
     *                          description="Продажа 1 или аренда 2",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="country_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="district",
     *                          type="string",
     *                          example="Город, которого нет в бд"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          example="Пушкина 1"
     *                      ),
     *                      @OA\Property(
     *                          property="longitude",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="latitude",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="currency_id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="number",
     *                          example="10000000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_day",
     *                          type="number",
     *                          example="3000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_week",
     *                          type="number",
     *                          example="10000"
     *                      ),
     *                      @OA\Property(
     *                          property="price_month",
     *                          type="number",
     *                          example="35000"
     *                      ),
     *                      @OA\Property(
     *                          property="not_show_price",
     *                          type="boolean",
     *                          example="0"
     *                      ),
     *                      @OA\Property(
     *                          property="rooms",
     *                          type="number",
     *                          example="2"
     *                      ),
     *                      @OA\Property(
     *                          property="bedrooms",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="bathrooms",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="square",
     *                          type="number",
     *                          example="40"
     *                      ),
     *                      @OA\Property(
     *                          property="square_land",
     *                          type="number",
     *                          example="40"
     *                      ),
     *                      @OA\Property(
     *                          property="square_land_unit",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="floor",
     *                          type="number",
     *                          example="10"
     *                      ),
     *                      @OA\Property(
     *                          property="total_floor",
     *                          type="number",
     *                          example="10"
     *                      ),
     *                      @OA\Property(
     *                          property="building_type",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="building_date",
     *                          type="string",
     *                          example="15.05.2012"
     *                      ),
     *                      @OA\Property(
     *                          property="specialtxt",
     *                          type="string",
     *                          example="Специальный текст, специально для сайта"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                          example="Описание, которые покорит сердца покупателя"
     *                      ),
     *                      @OA\Property(
     *                          property="filename",
     *                          type="string",
     *                          example="https://www.youtube.com/watch?v=4KZ2GeRWs1g"
     *                      ),
     *                      @OA\Property(
     *                          property="tour_link",
     *                          type="string",
     *                          example="https://www.youtube.com/watch?v=4KZ2GeRWs1g"
     *                      ),
     *                      @OA\Property(
     *                          property="propertie_values[]",
     *                          type="string",
     *                          format="number",
     *                      ),
     *                      @OA\Property(
     *                          property="properties_delete[]",
     *                          type="string",
     *                          format="number",
     *                      ),
     *                      @OA\Property(
     *                          property="images[]",
     *                          type="string",
     *                          format="binary",
     *                      ),
     *                      @OA\Property(
     *                          property="images_delete[]",
     *                          type="number",
     *                      ),
     *                      @OA\Property(
     *                          property="_method",
     *                          type="string",
     *                          example="PUT"
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FlatSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The title field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="square", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The square field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="district_id", type="array", collectionFormat="multi",
     *                          @OA\Items(
     *                          type="string",
     *                          example="The district_id field is required.",
     *                          )
     *                      ),
     *                 ),
     *          )
     *      )
     * )
     */
    public function update(UpdateFlatRequest $request, int $id)
    {
        $flat = Flat::findOrFail($id);

        if (auth()->user()->cannot('update', $flat)) return abort(403, 'No access');

        $values = $request->only([
            'object_id',
            'type_id',
            'country_id',
            'district_id',
            'district',
            'address',
            'longitude',
            'latitude',
            'currency_id',
            'price',
            'price_day',
            'price_week',
            'price_month',
            'not_show_price',
            'rooms',
            'bedrooms',
            'bathrooms',
            'square',
            'square_land',
            'square_land_unit',
            'floor',
            'total_floor',
            'building_type',
            'building_date',
            'specialtxt',
            'description',
            'filename',
            'tour_link',
        ]);

        $flat->update([
            ...$values
        ]);

        FlatPropertyController::createProperites($request->propertie_values, $flat);
        FlatPropertyController::deleteProperties($request->properties_delete, $flat);

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $flat, 'flat');
        if (!empty($request->images_delete)) ImageDBUtil::deleteImage($request->images_delete, $id, 'flat');

        return new JsonResponse(
            [
                'data' => Flat::find($flat->id)
            ],
            201
        );
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Flat id",
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
        $flat = Flat::findOrFail($id);

        if (auth()->check() && auth()?->user()?->cannot('delete', $flat)) return abort(403, 'No access');

        $delete_image_ids = collect($flat->images())->map(function ($item) {
            return $item->id;
        });
        ImageDBUtil::deleteImage([...$delete_image_ids], $id, 'flat');
        Flat::destroy($id);

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ],
            204
        );
    }
}
