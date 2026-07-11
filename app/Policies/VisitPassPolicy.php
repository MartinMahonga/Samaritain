<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitPass;

class VisitPassPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can see their own passes
    }

    public function view(User $user, VisitPass $visitPass): bool
    {
        return $user->id === $visitPass->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create a visit pass
    }

    public function retryPayment(User $user, VisitPass $visitPass): bool
    {
        return $user->id === $visitPass->user_id && $visitPass->isPaymentFailed();
    }
}
