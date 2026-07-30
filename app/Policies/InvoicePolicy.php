<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\Property;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        $property = Property::find($invoice->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        $property = Property::find($invoice->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        $property = Property::find($invoice->property_id);

        return $property && $property->created_by === $user->id;
    }
}
