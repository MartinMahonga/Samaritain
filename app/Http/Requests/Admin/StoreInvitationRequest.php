<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-members');
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', Rule::unique('agency_invitations', 'email')->whereNull('accepted_at')],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
