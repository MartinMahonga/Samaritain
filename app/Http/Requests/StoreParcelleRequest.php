<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'localisation' => ['required', 'string'],
            'arrondissement_id' => ['required', 'exists:arrondissements,id'],
            'ville' => ['required', 'string'],
            'superficie' => ['required', 'numeric', 'min:1'],
            'prix' => ['required', 'numeric', 'min:0'],
            'statut' => ['nullable', 'in:disponible,vendu,réservé'],
            'titre_foncier' => ['nullable', 'string', 'max:255'],
            'viabilisee' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'conditions' => ['required', 'accepted'],
        ];
    }
}
