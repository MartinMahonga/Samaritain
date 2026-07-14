<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtisanProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,png,jpg,webp'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120', 'mimes:jpeg,png,jpg,webp'],
        ];
    }
}
