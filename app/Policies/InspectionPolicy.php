<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\Property;
use App\Models\User;

class InspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Inspection $inspection): bool
    {
        $property = Property::find($inspection->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Inspection $inspection): bool
    {
        $property = Property::find($inspection->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function delete(User $user, Inspection $inspection): bool
    {
        $property = Property::find($inspection->property_id);

        return $property && $property->created_by === $user->id;
    }
}
