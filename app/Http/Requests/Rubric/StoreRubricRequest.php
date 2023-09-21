<?php

namespace App\Http\Requests\Rubric;

use App\Models\Rubric;
use Illuminate\Foundation\Http\FormRequest;

class StoreRubricRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->cannot('create', Rubric::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|unique:rubrics,name|max:255'
        ];
    }
}
