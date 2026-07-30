<?php

namespace App\Policies;

use App\Models\Intervention;
use App\Models\Property;
use App\Models\User;

class InterventionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Intervention $intervention): bool
    {
        $property = Property::find($intervention->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Intervention $intervention): bool
    {
        $property = Property::find($intervention->property_id);

        return $property && $property->created_by === $user->id;
    }

    public function delete(User $user, Intervention $intervention): bool
    {
        $property = Property::find($intervention->property_id);

        return $property && $property->created_by === $user->id;
    }
}
