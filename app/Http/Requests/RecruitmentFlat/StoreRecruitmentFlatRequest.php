<?php

namespace App\Http\Requests\RecruitmentFlat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecruitmentFlatRequest extends FormRequest
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
            'recruitment_id' => 'required|' . Rule::exists('recruitments', 'id'),
            'flat_id' => 'required|' . Rule::exists('flats', 'id'),
        ];
    }
}
