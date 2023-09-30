<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', Post::class);
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
            'district_id' => 'required|numeric|' . Rule::exists('districts', 'id'),
            'rubric_id' => 'required|numeric|' . Rule::exists('rubrics', 'id'),
            'source' => 'required',
            'images' => 'nullable|string',
            'main_image' => 'nullable|numeric|' . Rule::exists('files', 'id'),
        ];
    }
}
