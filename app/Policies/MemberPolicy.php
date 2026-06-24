<?php

namespace App\Policies;

use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        // Simplifié pour test
        return $user->hasRole('owner') || $user->can('manage-members');
    }

    public function view(User $user, User $member): bool
    {
        if (! $member->is_staff) {
            return false;
        }

        return $user->hasRole('owner') || $user->can('manage-members');
    }

    public function update(User $user, User $member): bool
    {
        if (! $member->is_staff) {
            return false;
        }
        if ($member->id === $user->id) {
            return false;
        }

        return $user->hasRole('owner') || $user->can('manage-members');
    }

    public function delete(User $user, User $member): bool
    {
        if (! $member->is_staff) {
            return false;
        }
        if ($member->id === $user->id) {
            return false;
        }

        return $user->hasRole('owner');
    }

    public function activate(User $user, User $member): bool
    {
        return $this->update($user, $member);
    }

    public function deactivate(User $user, User $member): bool
    {
        return $this->update($user, $member);
    }
}
