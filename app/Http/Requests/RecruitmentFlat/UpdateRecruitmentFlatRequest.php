<?php

namespace App\Http\Requests\RecruitmentFlat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecruitmentFlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'comment' => 'nullable|max:255',
        ];
    }
}
