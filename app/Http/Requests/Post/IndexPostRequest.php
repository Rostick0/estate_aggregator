<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexPostRequest extends FormRequest
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
            'title' => 'string',
            'district_id' => 'numeric|' . Rule::exists('districts', 'id'),
            'rubric_id' => 'numeric|' . Rule::exists('rubrics', 'id'),
            'page' => 'numeric',
            'limit' => 'numeric|max:50',
            'extends' => 'array'
        ];
    }
}
