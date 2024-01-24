<?php

namespace App\Http\Requests\Alert;

use App\Models\Alert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', Alert::class);
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
            'recipient_id' => 'nullable|' . Rule::exists('users', 'id'),
            'role' => 'nullable|in:client,realtor,agency,builder',
            'type',
        ];
    }
}
