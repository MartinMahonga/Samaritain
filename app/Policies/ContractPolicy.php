<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id;
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id && $contract->canBeDeleted();
    }

    public function cancel(User $user, Contract $contract): bool
    {
        return $contract->created_by === $user->id && $contract->canBeCancelled();
    }

    public function sign(User $user, Contract $contract): bool
    {
        if ($contract->created_by === $user->id) {
            return $contract->status === 'pending_owner';
        }

        if ($user->email === $contract->tenant_email) {
            return $contract->status === 'pending_tenant';
        }

        return false;
    }
}
