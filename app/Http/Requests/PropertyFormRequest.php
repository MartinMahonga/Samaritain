<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyFormRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $imageRules = $this->isMethod('POST')
        ? ['required', 'array', 'min:1']
        : ['nullable', 'array'];

        $rules = [
            'title' => ['required', 'min:8'],
            'description' => ['nullable', 'min:8'],
            'price' => ['required', 'integer', 'min:0'],
            'surface' => ['required', 'integer', 'min:10'],
            'rooms' => ['required', 'integer', 'min:1'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'floor' => ['required', 'integer', 'min:0'],
            'furnished' => ['nullable', 'boolean'],
            'address' => ['required', 'min:8'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['nullable', 'min:3'],
            'category_id' => ['required', 'exists:categories,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'arrondissement_id' => ['nullable', 'exists:arrondissements,id'],
            'amenities' => ['nullable', 'exists:amenities,id'],
            'images' => $imageRules,
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:10120'],
            'cover_image' => ['nullable', 'boolean'],
            'kept_images' => ['nullable', 'array'],
            'kept_images.*' => ['integer', 'exists:property_images,id'],
        ];

        if ($this->isMethod('POST')) {
            $rules['conditions'] = ['required', 'accepted'];
        }

        if ($this->user() && $this->user()->isStaff()) {
            $rules['is_verify'] = ['nullable', 'boolean'];
            $rules['is_active'] = ['nullable', 'boolean'];
        }

        return $rules;
    }
}
