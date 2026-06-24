<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|string|exists:passes,uuid',
        ];
    }
}
