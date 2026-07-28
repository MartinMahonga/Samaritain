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

    public function sign(User $user, Contract $contract): bool
    {
        if ($user->hasRole('owner')) {
            return $contract->created_by === $user->id && $contract->status === 'pending_owner';
        }

        if ($user->hasRole('tenant')) {
            return $user->email === $contract->tenant_email && $contract->status === 'pending_tenant';
        }

        return false;
    }
}
