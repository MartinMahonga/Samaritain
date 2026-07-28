<?php

namespace App\Http\Requests\Owner;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Must own the property being leased
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
            'tenant_name' => 'required|string|max:255',
            'tenant_email' => 'nullable|email|max:255',
            'tenant_phone' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'monthly_rent' => 'required|integer|min:0',
            'deposit' => 'nullable|integer|min:0',
            'status' => 'required|string|in:active,terminated,pending',
            'signature' => 'nullable|string',
        ];
    }
}
