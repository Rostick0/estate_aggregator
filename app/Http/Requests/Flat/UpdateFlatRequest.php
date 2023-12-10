<?php

namespace App\Http\Requests\Flat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'object_id' => 'numeric|' . Rule::exists('object_flats', 'id'),
            'type_id' => 'numeric|' . Rule::exists('flat_types', 'id'),
            'country_id' => 'numeric|' . Rule::exists('countries', 'id'),
            'district_id' => 'numeric|' . Rule::exists('districts', 'id'),
            'district_string' => 'nullable|string',
            'address' => 'string',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'currency_id' => 'numeric|' . Rule::exists('currencies', 'id'),
            'price' => 'numeric',
            'price_day' => '',
            'price_week' => '',
            'price_month' => '',
            'not_show_price' => 'boolean',
            'rooms' => 'numeric',
            'bedrooms' => 'numeric',
            'bathrooms' => 'numeric',
            'square' => '',
            'square_land' => '',
            'square_land_unit' => 'numeric|' . Rule::exists('square_land_units', 'id'),
            'floor' => 'numeric',
            'total_floor' => 'numeric',
            'building_type' => 'numeric|' . Rule::exists('building_types', 'id'),
            'building_date' => 'string',
            'specialtxt' => 'string|max:255',
            'description' => 'string|max:65536',
            'filename' => 'string|max:255',
            'tour_link' => 'string|max:255',
            'properties_values' => 'nullable',
            'images' => 'nullable|string',
        ];
    }
}
