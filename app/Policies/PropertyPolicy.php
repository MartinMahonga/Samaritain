<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function view(?User $user, Property $property): bool
    {
        if ($property->is_active && $property->is_verify) {
            return true;
        }

        return $user !== null && ($user->id === $property->created_by || $user->isAdmin());
    }

    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->created_by || $user->isAdmin();
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->created_by || $user->isAdmin();
    }
}
