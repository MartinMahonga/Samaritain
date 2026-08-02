<?php

namespace App\Http\Requests\Owner;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $propertyId = $this->input('property_id');
        if ($propertyId) {
            $property = Property::find($propertyId);

            return $property && $property->created_by === auth()->id();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'nullable|exists:properties,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:invoice,receipt,quote,inspection,other',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // 10MB max
        ];
    }
}
