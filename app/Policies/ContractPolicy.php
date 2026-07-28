<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('owner');
    }

    public function view(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner');
    }

    public function update(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id;
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id;
    }
}
