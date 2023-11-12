<?php

namespace App\Http\Requests\AlertUserRequest;

use App\Models\AlertUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlertUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', AlertUser::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'alert_id' => 'numeric|' . Rule::exists('alerts', 'id'),
            'user_id' => 'nullable|' . Rule::exists('users', 'id'),
        ];
    }
}
