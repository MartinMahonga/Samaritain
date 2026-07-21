<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcelleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'titre' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'localisation' => ['sometimes', 'string'],
            'arrondissement_id' => ['sometimes', 'exists:arrondissements,id'],
            'ville' => ['sometimes', 'string'],
            'superficie' => ['sometimes', 'numeric', 'min:1'],
            'prix' => ['sometimes', 'numeric', 'min:0'],
            'statut' => ['sometimes', 'in:disponible,vendu,réservé'],
            'titre_foncier' => ['nullable', 'string', 'max:255'],
            'viabilisee' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'kept_images' => ['nullable', 'array'],
            'kept_images.*' => ['integer', 'exists:parcelle_images,id'],
        ];
    }
}