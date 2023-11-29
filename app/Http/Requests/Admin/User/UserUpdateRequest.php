<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => ['nullable', 'email', 'unique:users,email,' . $this->user, 'max:255'],
            'phone' => 'required|max:30',
            'image_id' => 'nullable|' . Rule::exists('images', 'id'),
            'country_id' => 'nullable|' . Rule::exists('countries', 'id'),
            'type_social' => 'nullable|in:whatsapp,viber,telegram',
            'is_confirm' => 'required',
            'company_id' => 'nullable|' . Rule::exists('companies', 'id'),
        ];
    }
}
