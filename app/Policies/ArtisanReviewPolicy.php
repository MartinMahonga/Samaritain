<?php

namespace App\Policies;

use App\Models\ArtisanReview;
use App\Models\User;

class ArtisanReviewPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ArtisanReview $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, ArtisanReview $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }
}
