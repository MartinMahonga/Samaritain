<?php

namespace App\Policies;

use App\Models\Pass;
use App\Models\User;

class PassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner', 'commercial', 'agent']);
    }

    public function view(User $user, Pass $pass): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner', 'commercial', 'agent']);
    }

    public function create(User $user): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner', 'commercial', 'agent']);
    }

    public function update(User $user, Pass $pass): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner']);
    }

    public function delete(User $user, Pass $pass): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner']);
    }

    public function scan(User $user): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner', 'commercial', 'agent']);
    }
}
