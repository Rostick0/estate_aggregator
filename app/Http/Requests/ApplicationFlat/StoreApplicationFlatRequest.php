<?php

namespace App\Http\Requests\ApplicationFlat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationFlatRequest extends FormRequest
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
            'flat_id' => 'requred|numeric|' . Rule::exists('flats', 'id'),
            'is_information',
            'is_viewing',
            'name' => 'requred|max:255',
            'phone' => 'requred|max:255',
            'email' => 'email|max:255',
            'text' => 'requred|min:10|max:255',
            'messager_type' => 'in:telegram,whatsapp,viber',
        ];
    }
}
