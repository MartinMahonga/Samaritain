<?php

namespace App\Http\Requests\Owner;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $propertyId = $this->input('property_id');
        if (! $propertyId) {
            return false;
        }

        $property = Property::find($propertyId);

        return $property && $property->created_by === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'type' => 'required|string|in:check_in,check_out',
            'date' => 'required|date',
            'inspector_name' => 'required|string|max:255',
            'rooms_data' => 'required|array',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tenant_signature' => 'nullable|string|max:255',
            'owner_signature' => 'nullable|string|max:255',
        ];
    }
}
