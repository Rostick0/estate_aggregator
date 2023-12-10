<?php

namespace App\Http\Requests\Flat;

use App\Models\Flat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', Flat::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'object_id' => 'required|numeric|' . Rule::exists('object_flats', 'id'),
            'type_id' => 'required|numeric|' . Rule::exists('flat_types', 'id'),
            'country_id' => 'required|numeric|' . Rule::exists('countries', 'id'),
            'district_id' => 'numeric|' . Rule::exists('districts', 'id'),
            'district_string' => 'nullable|string',
            'address' => 'nullable|string',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'currency_id' => 'required|numeric|' . Rule::exists('currencies', 'id'),
            'price' => '',
            'price_day' => '',
            'price_week' => '',
            'price_month' => '',
            'not_show_price' => 'nullable|boolean',
            'rooms' => 'required|numeric',
            'bedrooms' => 'required|numeric',
            'bathrooms' => 'required|numeric',
            'square' => '',
            'square_land' => '',
            'square_land_unit' => 'numeric|' . Rule::exists('square_land_units', 'id'),
            'floor' => 'required|numeric',
            'total_floor' => 'required|numeric',
            'building_type' => 'required|numeric|' . Rule::exists('building_types', 'id'),
            'building_date' => 'nullable|string',
            'specialtxt' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65536',
            'filename' => 'nullable|string|max:255',
            'tour_link' => 'nullable|string|max:255',
            'properties_values' => 'nullable',
            'images' => 'nullable|string',
        ];
    }
}
