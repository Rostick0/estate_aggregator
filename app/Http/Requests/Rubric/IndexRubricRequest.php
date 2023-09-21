<?php

namespace App\Http\Requests\Rubric;

use Illuminate\Foundation\Http\FormRequest;

class IndexRubricRequest extends FormRequest
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
            'filterEQ' => 'array',
            'filterLIKE' => 'array',
            'page' => 'numeric',
            'limit' => 'numeric|max:150',
            'extends' => 'string'
        ];
    }
}
