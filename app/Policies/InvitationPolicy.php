<?php

namespace App\Policies;

use App\Models\AgencyInvitation;
use App\Models\User;

class InvitationPolicy
{
    public function view(User $user): bool
    {
        return $user->hasRole('owner') || $user->can('manage-members');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-members');
    }

    public function delete(User $user, AgencyInvitation $invitation): bool
    {
        if ($invitation->isAccepted()) {
            return false;
        }

        return $user->can('manage-members');
    }

    public function resend(User $user, AgencyInvitation $invitation): bool
    {
        if ($invitation->isAccepted()) {
            return false;
        }

        return $user->can('manage-members');
    }
}
