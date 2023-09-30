<?php

namespace App\Http\Requests\District;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistrictRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('update', District::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'max:255',
                'unique:districts,name' . $this->id
            ],
            'importance' => 'numeric',
            'region_id' => 'numeric|' . Rule::exists('regions', 'id'),

        ];
    }
}
