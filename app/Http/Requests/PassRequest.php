<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'holder_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'allowed_visits' => 'required|integer|min:1|max:1000',
            'start_date' => 'required|date|after_or_equal:today',
            'expiration_date' => 'required|date|after:start_date',
            'regenerate_qr' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'holder_name.required' => 'Le nom du titulaire est requis',
            'phone.required' => 'Le numéro de téléphone est requis',
            'allowed_visits.min' => 'Le nombre de visites doit être au moins 1',
            'expiration_date.after' => 'La date d\'expiration doit être après la date de début',
        ];
    }
}
