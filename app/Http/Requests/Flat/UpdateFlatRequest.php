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
            'object_id' => 'required|numeric|' . Rule::exists('object_flats', 'id'),
            'type_id' => 'required|numeric|' . Rule::exists('flat_types', 'id'),
            'country_id' => 'required|numeric|' . Rule::exists('countries', 'id'),
            'district_id' => 'numeric|' . Rule::exists('districts', 'id'),
            'district' => 'string',
            'address' => 'string',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'currency_id' => 'required|numeric|' . Rule::exists('currencies', 'id'),
            'price' => '',
            'price_day' => '',
            'price_week' => '',
            'price_month' => '',
            'not_show_price' => 'boolean',
            'rooms' => 'required|numeric',
            'bedrooms' => 'required|numeric',
            'bathrooms' => 'required|numeric',
            'square' => 'required',
            'square_land' => 'required',
            'square_land_unit' => 'required|numeric|' . Rule::exists('square_land_units', 'id'),
            'floor' => 'required|numeric',
            'total_floor' => 'required|numeric',
            'building_type' => 'required|numeric|' . Rule::exists('building_types', 'id'),
            'building_date' => 'string',
            'specialtxt' => 'string|max:255',
            'description' => 'string|max:65536',
            'filename' => 'string|max:255',
            'tour_link' => 'string|max:255',
            'properties_values' => 'string',
            'images' => 'nullable|string',
            'properties_delete' => 'nullable|array',
            'properties_delete.*' => 'numeric',
        ];
    }
}
