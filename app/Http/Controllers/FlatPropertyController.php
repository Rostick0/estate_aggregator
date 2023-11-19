<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;

class FlatPropertyController extends Controller
{
    private static function emptyProperty($properties)
    {
        return empty($properties) || isset($properties);
    }

    public static function createProperites($properties_values, Flat $flat)
    {
        $properties_values = Json::decode($properties_values);

        if (!is_array($properties_values)) $properties_values = [$properties_values];

        foreach ($properties_values as $item) {
            $item = (object) $item;

            $flat->flat_properties()->create([
                'value_enum' => $item?->value_enum ?? null,
                'value' => $item?->value ?? null,
                'property_id' =>  $item->property_value_id ?? null,
                'property_value_id' => $item->property_value_id,
            ]);
        }
    }
}
