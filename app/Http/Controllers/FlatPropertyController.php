<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;

class FlatPropertyController extends Controller
{
    public static function createProperites($propertie_values, Flat $flat)
    {
        if (empty($propertie_values)) return;

        foreach ($propertie_values as $item) {
            $flat->flat_properties()->create([
                'value' => $item->value,
                'property_value_id' => $item->property_value_id,
            ]);
        }
    }

    public static function deleteProperties($properties_delete_ids, Flat $flat)
    {
        if (empty($properties_delete_ids)) return;

        $flat->flat_properties()->whereIn('properties_delete_ids', $properties_delete_ids)->delete();
    }
}