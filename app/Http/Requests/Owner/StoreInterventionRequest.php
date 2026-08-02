<?php

namespace App\Http\Requests\Owner;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInterventionRequest extends FormRequest
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
            'artisan_id' => 'nullable|exists:artisans,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:plumbing,painting,roofing,locksmith,garden,heating,appliances,other',
            'urgency' => 'required|string|in:low,medium,high,emergency',
            'cost' => 'required|integer|min:0',
            'is_renovation' => 'nullable|boolean',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'scheduled_at' => 'nullable|date',
        ];
    }
}
