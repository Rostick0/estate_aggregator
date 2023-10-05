<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flat\IndexFlatRequest;
use App\Http\Requests\Flat\ShowFlatRequest;
use App\Models\Flat;
use App\Http\Requests\Flat\StoreFlatRequest;
use App\Http\Requests\Flat\UpdateFlatRequest;
use App\Http\Requests\Flat\UploadFlatRequest;
use App\Models\File;
use App\Models\FileRelationship;
use App\Models\FlatProperty;
use App\Utils\FileRelationUtil;
use App\Utils\QueryString;
use App\Utils\FilterRequestUtil;
use App\Utils\OrderByUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\LazyCollection;
use SimpleXMLElement;

class FlatController extends Controller
{
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
     *          name="filterHas[flat_properties.property_value.property]",
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
        $data_init = Flat::with(QueryString::convertToArray($request->extends));

        $data_init->where(FilterRequestUtil::eq($request->filterEQ));
        $data_init->where(FilterRequestUtil::like($request->filterLIKE));
        $data_init = FilterRequestUtil::has($request->filterHas, $data_init);
        $data_init = OrderByUtil::set($request->sort, $data_init);

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
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"object_id", "type_id", "country_id", "district_id", "currency_id", "price"},
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

        FileRelationUtil::createAndDelete(
            $flat->files(),
            QueryString::convertToArray($request->images)
        );

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
        $flat = Flat::with(QueryString::convertToArray($request->extends))
            ->findOrFail($id);

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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      required={"object_id", "type_id", "country_id", "district_id", "currency_id", "price"},
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

        if ($request->properties_values) FlatPropertyController::createProperites($request->properties_values, $flat);
        if ($request->properties_delete) FlatPropertyController::deleteProperties(explode(',', $request->properties_delete), $flat);

        FileRelationUtil::createAndDelete(
            $flat->files(),
            QueryString::convertToArray($request->images)
        );


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

        return new JsonResponse(
            [
                'message' => 'Deleted'
            ]
        );
    }

    public function upload(UploadFlatRequest $requst)
    {
        $xml_data = new SimpleXMLElement(
            file_get_contents($requst->file)
        );

        LazyCollection::make(function () use ($xml_data) {
            foreach ($xml_data->objects->object as $item) {
                yield $item;
                yield (object) [
                    // 'data' => [
                    //     'id ' => $item?->id,
                    //     'object_id ' => $item?->object_id,
                    //     'type_id' => $item?->type_id,
                    //     'country_id' => $item?->country_id,
                    //     'district_id' => $item?->district_id,
                    //     'district' => $item?->district,
                    //     'address' => $item?->address,
                    //     'longitude' => $item?->longitude,
                    //     'latitude' => $item?->latitude,
                    //     'currency_id' => $item?->currency_id,
                    //     'price' => $item?->price,
                    //     'price_per_meter' => $item?->price_per_meter,
                    //     'price_day' => $item?->price_day,
                    //     'price_week' => $item?->price_week,
                    //     'price_month' => $item?->price_month,
                    //     'not_show_price' => $item?->not_show_price == 'None' ? 0 : 1,
                    //     'rooms' => $item?->rooms,
                    //     'bedrooms' => $item?->bedrooms,
                    //     'bathrooms' => $item?->bathrooms,
                    //     'square' => (int) $item?->square,
                    //     'square_land' => $item?->square_land,
                    //     'square_land_unit' => $item?->square_land_unit,
                    //     'floor' => $item?->floor,
                    //     'total_floor' => $item?->total_floor,
                    //     'building_type' => $item?->building_type,
                    //     'building_date' => $item?->building_date,
                    //     'contact_id' => $item?->contact_id,
                    //     'specialtxt' => $item?->specialtxt,
                    //     'description' => $item?->description,
                    //     'filename' => $item?->filename,
                    //     'tour_link' => $item?->tour_link,
                    // ],
                    // 'user' => [
                    //     'id' => $item->contact_id,
                    //     'name' => $item->contact->name,
                    //     'email' => $item->contact->email,
                    //     'phone' => $item->contact->phone,
                    //     'avatar' => $item->contact->photo,
                    // ],
                    'user' => $item->contact,
                    'images' => $item->images,
                    'properties' => $item->properties
                ];
            }
        })
            ->chunk(250)
            ->each(function ($elem) {
                $elem->each(function ($item) {
                    // dd($item?->images);
                    // $flat = Flat::firstOrCreate(
                    //     ['id' => $item?->id],
                    //     [
                    //         'id ' => $item?->id,
                    //         'object_id ' => $item?->object_id,
                    //         'type_id' => $item?->type_id,
                    //         'country_id' => $item?->country_id,
                    //         'district_id' => $item?->district_id,
                    //         'district' => $item?->district,
                    //         'address' => $item?->address,
                    //         'longitude' => $item?->longitude,
                    //         'latitude' => $item?->latitude,
                    //         'currency_id' => $item?->currency_id,
                    //         'price' => $item?->price,
                    //         'price_per_meter' => $item?->price_per_meter,
                    //         'price_day' => $item?->price_day,
                    //         'price_week' => $item?->price_week,
                    //         'price_month' => $item?->price_month,
                    //         'not_show_price' => $item?->not_show_price == 'None' ? 0 : 1,
                    //         'rooms' => $item?->rooms,
                    //         'bedrooms' => $item?->bedrooms,
                    //         'bathrooms' => $item?->bathrooms,
                    //         'square' => (int) $item?->square,
                    //         'square_land' => $item?->square_land,
                    //         'square_land_unit' => $item?->square_land_unit,
                    //         'floor' => $item?->floor,
                    //         'total_floor' => $item?->total_floor,
                    //         'building_type' => $item?->building_type,
                    //         'building_date' => $item?->building_date,
                    //         'contact_id' => $item?->contact_id,
                    //         'specialtxt' => $item?->specialtxt,
                    //         'description' => $item?->description,
                    //         'filename' => $item?->filename,
                    //         'tour_link' => $item?->tour_link,
                    //     ]
                    // );

                    // $user = $flat->user()->update([
                    //     'name' => $item?->contact?->name,
                    //     'email' => $item?->contact?->email,
                    //     'phone' => $item?->contact?->phone,
                    //     'avatar' => $item?->contact?->photo,
                    // ]);

                    // $user->contacts()->delete();

                    // foreach (explode(',', $item?->contact->messengers) as $messager) {
                    //     $user->contacts()->create([
                    //         'type' => $messager
                    //     ]);
                    // }

                    // $flat->files->delete();

                    // foreach ($item?->images->image as $image) {
                    //     $file = File::firstOrCreate([
                    //         'path' => (string) $image->filename[0],
                    //         'type' => 'image/' . pathinfo($image->filename[0], PATHINFO_EXTENSION),
                    //         'user_id' => $item?->contact_id
                    //     ]);

                    //     $flat->files->create([
                    //         'file_id' => $file->id
                    //     ]);
                    // }

                    // $flat->flat_properties()->delete();

                    // foreach ($item?->properties->property as $property) {
                    //     if (!empty($property->property_value_enum)) {
                    //         if (is_array($property->property_value_enum)) {
                    //             foreach ($property->property_value_enum as $item) {
                    //                 $flat->flat_properties()->create([
                    //                     'value_enum' => $item,
                    //                     'property_value_id' => $property->property_id,
                    //                 ]);
                    //             }
                    //         } else {
                    //             $flat->flat_properties()->create([
                    //                 'value_enum' => $property->property_value_enum,
                    //                 'property_value_id' => $property->property_id,
                    //             ]);
                    //         }
                    //     }

                    //     if (!empty($property->property_value)) {
                    //         if (is_array($property->property_value)) {
                    //             foreach ($property->property_value as $item) {
                    //                 $flat->flat_properties()->create([
                    //                     'value' => $item,
                    //                     'property_value_id' => $property->property_id,
                    //                 ]);
                    //             }
                    //         } else {
                    //             $flat->flat_properties()->create([
                    //                 'value' => $property->property_value,
                    //                 'property_value_id' => $property->property_id,
                    //             ]);
                    //         }
                    //     }
                    // }
                });

                sleep(0.05);
            });
    }
}
