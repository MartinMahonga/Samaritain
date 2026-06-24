<?php

namespace App\Policies;

use App\Models\ArtisanProject;
use App\Models\User;

class ArtisanProjectPolicy
{
    public function create(): bool
    {
        return true;
    }

    public function update(User $user, ArtisanProject $project): bool
    {
        return $user->id === $project->artisan->user_id;
    }

    public function delete(User $user, ArtisanProject $project): bool
    {
        return $user->id === $project->artisan->user_id;
    }
}
