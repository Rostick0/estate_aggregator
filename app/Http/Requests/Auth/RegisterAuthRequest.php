<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email|max:255',
            'password' => 'required|min:6|max:255',
            'phone' => 'required|unique:users,phone|max:30',
            'role' => 'required|in:client,realtor,agency,builder',
            'type_social' => 'nullable|in:whatsapp,viber,telegram',
        ];
    }
}
