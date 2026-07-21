<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitPassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'property_id' => ['required', 'exists:properties,id'],
            'holder_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'La propriété est requise.',
            'property_id.exists' => 'La propriété sélectionnée n\'existe pas.',
            'holder_name.required' => 'Le nom complet est requis.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
        ];
    }
}
