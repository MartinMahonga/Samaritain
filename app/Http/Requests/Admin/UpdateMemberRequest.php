<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-members');
    }

    public function rules(): array
    {
        $userId = $this->route('member');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'role_id' => ['required', 'exists:roles,id'],
            'is_active' => ['boolean'],
        ];
    }
}
