<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-settings') ?? false;
    }

    public function rules(): array
    {
        $amenity = $this->route('amenity');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:amenities,name,'.$amenity->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:amenities,slug,'.$amenity->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
