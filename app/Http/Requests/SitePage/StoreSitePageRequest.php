<?php

namespace App\Http\Requests\SitePage;

use Illuminate\Foundation\Http\FormRequest;

class StoreSitePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'keywords' => 'nullable|max:255',
            'robots' => 'nullable|max:255',
            'path' => 'required|unique:site_pages,path|max:255',
        ];
    }
}
