<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email|max:255',
            'phone' => 'required|max:255',
            'full_name' => 'nullable|max:255',
            'text' => 'nullable|max:65536',
            'communiction_method' => 'nullable|max:255',
            'purpose' => 'nullable|max:255',
            'link_from' => 'nullable|max:255',
            'ticket_type_cid' => 'nullable|numeric|' . Rule::exists('collections', 'id')->where('type', 'ticket_types'),
            'status_cid' => 'nullable|numeric|' . Rule::exists('collections', 'id')->where('type', 'ticket_statuses'),
            'flat_id' => 'nullable|numeric|' . Rule::exists('flats', 'id'),
        ];
    }
}
