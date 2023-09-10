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
            'city_id' => 'numeric|' . Rule::exists('cities', 'id'),
            'rubric_id' => 'numeric|' . Rule::exists('rubrics', 'id'),
            'page' => 'numeirc',
            'limit' => 'numeric|max:50',
            'extends' => 'array'
        ];
    }
}
