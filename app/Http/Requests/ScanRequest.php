<?php

namespace App\Http\Requests;

use App\Models\Pass;
use App\Models\VisitPass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|string|uuid',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $uuid = $this->input('uuid');

            if (! $uuid || $validator->errors()->has('uuid')) {
                return;
            }

            $exists = Pass::where('uuid', $uuid)->exists()
                || VisitPass::where('uuid', $uuid)->exists();

            if (! $exists) {
                $validator->errors()->add('uuid', 'Le champ uuid sélectionné est invalide.');
            }
        });
    }
}
