<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-members');
    }

    public function rules(): array
    {
        // Récupérer l'ID du rôle owner pour l'exclure
        $ownerRole = Role::where('name', 'owner')->first();

        $rules = [
            'email' => ['required', 'email', 'max:255', Rule::unique('agency_invitations', 'email')->whereNull('accepted_at')],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        if ($ownerRole) {
            $rules['role_id'][] = Rule::notIn([$ownerRole->id]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'role_id.not_in' => 'Le rôle propriétaire ne peut pas être assigné via une invitation.',
        ];
    }
}
