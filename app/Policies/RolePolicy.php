<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('owner');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner');
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->name === 'owner') {
            return false;
        }

        return $user->hasRole('owner');
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->name === 'owner') {
            return false;
        }
        // Empêcher la suppression si le rôle est attribué à des utilisateurs
        if ($role->users()->count() > 0) {
            return false;
        }

        return $user->hasRole('owner');
    }
}
