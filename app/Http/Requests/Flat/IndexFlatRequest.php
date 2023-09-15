<?php

namespace App\Http\Requests\Flat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexFlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'object_id' => 'numeric|'  . Rule::exists('object_flats', 'id'),
            'type_id' => 'numeric|'  . Rule::exists('flat_types', 'id'),
            'currency_id' => 'numeric|'  . Rule::exists('currencies', 'id'),
            'price' => '',
            'country_id' => 'numeric|'  . Rule::exists('countries', 'id'),
            'district_id' => 'numeric|'  . Rule::exists('districts', 'id'),
            'search' => 'string',
            'page' => 'numeric',
            'limit' => 'numeric|max:50',
            'extends' => 'array',
            'flat_properties' => 'array',
        ];
    }
}
