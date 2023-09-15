<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flat\ShowFlatRequest;
use App\Models\Flat;
use App\Http\Requests\Flat\StoreFlatRequest;
use App\Http\Requests\Flat\UpdateFlatRequest;
use App\Utils\ImageDBUtil;
use Illuminate\Http\JsonResponse;

class FlatController extends Controller
{
    private static function createProperites($propertie_values, Flat $flat)
    {
        if (empty($propertie_values)) return;

        foreach ($propertie_values as $item) {
            $flat->flat_properties()->create([
                'value' => $item->value,
                'property_value_id' => $item->property_value_id,
            ]);
        }
    }

    private static function deleteProperties($properties_delete_ids, Flat $flat)
    {
        if (empty($properties_delete_ids)) return;

        $flat->flat_properties()->whereIn('properties_delete_ids', $properties_delete_ids)->delete();
    }

    public function index()
    {
        //
    }

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
            'price_per_meter',
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

        $this::createProperites($request->propertie_values, $flat);
        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $flat, 'flat');

        return new JsonResponse(
            [
                'data' => Flat::find($flat->id)
            ],
            201
        );
    }

    public function show(ShowFlatRequest $request, int $id)
    {
        $flat = Flat::with($request->extends ?? [])->findOrFail($id);

        return new JsonResponse(
            [
                'data' => $flat
            ],
        );
    }

    public function update(UpdateFlatRequest $request, int $id)
    {
        $flat = Flat::findOrFail($id);

        if (auth()?->user()?->cannot('update', $flat)) return abort(403, 'No access');

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
            'price_per_meter',
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

        $this::createProperites($request->propertie_values, $flat);
        $this::deleteProperties($request->properties_delete, $flat);

        if ($request->hasFile('images')) ImageDBUtil::uploadImage($request->file('images'), $flat, 'flat');
        if (!empty($request->images_delete)) ImageDBUtil::deleteImage($request->images_delete, $id, 'flat');

        return new JsonResponse(
            [
                'data' => Flat::find($flat->id)
            ],
            201
        );
    }

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
