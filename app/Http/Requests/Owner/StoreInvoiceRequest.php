<?php

namespace App\Http\Requests\Owner;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'type' => 'required|string|in:water,electricity,taxes,garbage,other',
            'amount' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ];
    }
}
