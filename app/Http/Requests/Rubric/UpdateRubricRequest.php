<?php

namespace App\Http\Requests\Rubric;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRubricRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->cannot('update', Rubric::class);
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
                'unique:rubrics,name' . $this->id
            ]
        ];
    }
}
