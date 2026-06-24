<?php

namespace App\Policies;

use App\Models\Artisan;
use App\Models\User;

class ArtisanPolicy
{
    public function viewAny(): bool
    {
        return true;
    }

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return ! Artisan::where('user_id', $user->id)->exists();
    }

    public function update(User $user, Artisan $artisan): bool
    {
        return $user->id === $artisan->user_id || $user->isAdmin();
    }

    public function delete(User $user, Artisan $artisan): bool
    {
        return $user->id === $artisan->user_id || $user->isAdmin();
    }

    public function verify(User $user): bool
    {
        return $user->isAdmin();
    }

    public function suspend(User $user): bool
    {
        return $user->isAdmin();
    }
}
