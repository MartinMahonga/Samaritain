<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtisanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'phone' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:artisan_categories,id'],
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'cover' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,png,jpg,webp'],
        ];
    }
}
