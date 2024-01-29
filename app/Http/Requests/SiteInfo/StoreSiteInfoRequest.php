<?php

namespace App\Http\Requests\SiteInfo;

use App\Models\SiteInfo;
use Illuminate\Foundation\Http\FormRequest;

class StoreSiteInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('create', SiteInfo::class);
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
            'text' => 'required|max:65536',
            'key' => 'required|max:255',
            'type' =>  'nullable|max:255',
        ];
    }
}
