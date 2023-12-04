<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string|max:255',
            'email' => ['nullable', 'email', 'unique:users,email,' . $this->user, 'max:255'],
            'phone' => 'string|max:30',
            'image_id' => 'nullable|' . Rule::exists('images', 'id'),
            'country_id' => 'nullable|' . Rule::exists('countries', 'id'),
            'type_social' => 'nullable|in:whatsapp,viber,telegram',
        ];
    }
}
