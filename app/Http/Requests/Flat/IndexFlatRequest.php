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
            'price' => '',
            'filterEQ' => 'array',
            'filterLIKE' => 'array',
            'sort' => '',
            'page' => 'numeric',
            'limit' => 'numeric|max:1000',
            'extends' => 'string',
            'flat_properties' => 'array',
        ];
    }
}
