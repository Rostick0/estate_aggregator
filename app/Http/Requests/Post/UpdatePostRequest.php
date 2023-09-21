<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd($this->route('post'));
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
            'title' => 'required|string|min:4',
            'content' => 'required|string|min:100|max:65536',
            'district_id' => 'numeric|' . Rule::exists('districts', 'id'),
            'rubric_id' => 'numeric|' . Rule::exists('rubrics', 'id'),
            'source' => 'required',
            'images' => 'array',
            'main_image' => 'nullable|mimes:png,jpg,jpeg,gif,svg',
            'images.*' => 'nullable|mimes:png,jpg,jpeg,gif,svg',
            'images_delete' => 'array',
            'images_delete.*' => 'nullable|numeric'
        ];
    }
}
