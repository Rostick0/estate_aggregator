<?php

namespace App\Http\Requests\Recruitment;

use App\Models\Recruitment;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecruitmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', Recruitment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255'
        ];
    }
}
