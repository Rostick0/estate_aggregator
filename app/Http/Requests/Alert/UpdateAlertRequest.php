<?php

namespace App\Http\Requests\Alert;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlertRequest extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'country_id' => 'nullable|' . Rule::exists('countries', 'id'),
            'role' => 'nullable|in:client,realtor,agency,builder',
            'type',
            'status' => 'required|in:active,archive,processing'
        ];
    }
}
