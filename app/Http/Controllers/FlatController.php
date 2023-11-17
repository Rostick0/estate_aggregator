<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Flat\IndexFlatRequest;
use App\Http\Requests\Flat\ShowFlatRequest;
use App\Models\Flat;
use App\Http\Requests\Flat\StoreFlatRequest;
use App\Http\Requests\Flat\UpdateFlatRequest;
use App\Utils\FileRelationUtil;
use App\Utils\QueryString;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;


class FlatController extends Controller
{
    private static function extendsMutation($data, $request)
    {
        $data->images()->delete();
        if ($request->images) {
            $images = array_map(function ($image_id) {
                return ['image_id' => $image_id];
            }, QueryString::convertToArray($request->images));

            $data->images()->createMany($images);
        }
    }

    private static function getWhere()
    {
        return [];
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/flat",
     *     tags={"Flat"},
     *     @OA\Parameter( 
     *          name="filterEQ[object_id]",
     *          description="object_id, type_id, country_id, district_id",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Parameter( 
     *          name="filterEQ[flat_properties.property_value.property]",
     *          description="Нужно привести в json формат {property_value_id:1,value:800}, чтобы протестировать",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number"
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
     *          example="flat_properties,object,type,country,district,currency,square_land_unit,building_type,user,files",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/FlatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexFlatRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Flat, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/flat",
     *     tags={"Flat"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"title", "object_id", "type_id", "country_id", "district_id", "currency_id", "price"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Заголовок",
     *                      ),
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
     *                          example="5"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="1702"
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
     *                          example="79"
     *                      ),
     *                      @OA\Property(
     *                          property="latitude",
     *                          type="string",
     *                          example="79"
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
     *                          description="1 - true, 0 - false",
     *                          type="true",
     *                          example="1"
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
     *                          example="129"
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
     *                          example="117"
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
     *                          property="properties_values",
     *                          type="string",
     *                          format="json",
     *                          description="Конвертируйте в json [{value:800,property_value_id:1}]"
     *                      ),
     *                      @OA\Property(
     *                          property="images",
     *                          description="Добавление по id файла, наример: 1,2,3",
     *                          description="Пример: 1,2,3",
     *                          type="string",
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
            'title',
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

        if ($request->properties_values) FlatPropertyController::createProperites($request->properties_values, $flat);

        $this::extendsMutation($flat, $request);

        return new JsonResponse([
            'data' => Flat::find($flat->id)
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     @OA\Parameter( 
     *          name="id",
     *          description="Id",
     *          in="path",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="flat_properties,object,type,country,district,currency,square_land_unit,building_type,user,files",
     *          @OA\Schema(
     *              type="string",
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
     *                  ref="#/components/schemas/FlatSchema"
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
        return new JsonResponse([
            'data' => Filter::one($request, new Flat, $id, $this::getWhere())
        ]);
    }

    /**
     * Update
     * @OA\Post (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     security={{"bearer_token": {}}},
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"title", "object_id", "type_id", "country_id", "district_id", "currency_id", "price"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="Название",
     *                      ),                     
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
     *                          example="5"
     *                      ),
     *                      @OA\Property(
     *                          property="district_id",
     *                          type="number",
     *                          example="1702"
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
     *                          example="79"
     *                      ),
     *                      @OA\Property(
     *                          property="latitude",
     *                          type="string",
     *                          example="79"
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
     *                          description="1 - true, 0 - false",
     *                          type="number",
     *                          example="1"
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
     *                          example="117"
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
     *                          property="properties_values",
     *                          type="string",
     *                          format="json",
     *                          description="Конвертируйте в json [{value:800,property_value_id:1}]"
     *                      ),
     *                      @OA\Property(
     *                          property="properties_delete",
     *                          description="Пример: 1,2,3",
     *                          type="string",
     *                          format="number",
     *                      ),
     *                      @OA\Property(
     *                          property="images",
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

        if (!auth()->check() || auth()->user()->cannot('update', $flat)) return new JsonResponse(
            [
                'message' => 'No access'
            ],
            403
        );

        $values = $request->only([
            'title',
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

        $flat->flat_properties()->delete();
        if ($request->properties_values) FlatPropertyController::createProperites($request->properties_values, $flat);

        $this::extendsMutation($flat, $request);

        return new JsonResponse([
            'data' => Flat::find($flat->id)
        ], 201);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/flat/{id}",
     *     tags={"Flat"},
     *     security={{"bearer_token": {}}},
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

        if (!auth()->check() || auth()?->user()?->cannot('delete', $flat)) return new JsonResponse(
            [
                'message' => 'No access'
            ],
            403
        );

        Flat::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
