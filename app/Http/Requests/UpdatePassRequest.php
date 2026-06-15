<?php

namespace App\Http\Requests;

use App\Models\Pass;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pass = $this->route('pass');
        return $pass && $this->user()->can('update', $pass);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'holder_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'total_visits' => ['required', 'integer', 'min:1', 'max:100'],
            'valid_from' => ['required', 'date', 'before_or_equal:expires_at'],
            'expires_at' => ['required', 'date', 'after_or_equal:valid_from'],
        ];
    }
}
