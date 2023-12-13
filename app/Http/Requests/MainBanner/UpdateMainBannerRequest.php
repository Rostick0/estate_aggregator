<?php

namespace App\Http\Requests\MainBanner;

use App\Models\MainBanner;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMainBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()?->user()?->can('update', MainBanner::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'regex:/^\d+(,\d+)*$/'],
        ];
    }
}
