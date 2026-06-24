<?php

namespace App\Policies;

use App\Models\Parcelle;
use App\Models\User;

class ParcellePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Parcelle $parcelle): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner']) || $user->can('manage-properties');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Parcelle $parcelle): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner']) || $user->can('manage-properties');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Parcelle $parcelle): bool
    {
        return $user->isStaff() || $user->hasRole(['admin', 'owner']) || $user->can('manage-properties');
    }

    /**
     * Determine whether the user can delete a specific image of the model.
     */
    public function deleteImage(User $user, Parcelle $parcelle): bool
    {
        return $this->update($user, $parcelle);
    }
}
