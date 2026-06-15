<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DeleteUserAccount
{
    /**
     * Delete the user's account after password confirmation.
     *
     * @throws ValidationException
     */
    public function delete(User $user, string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Le mot de passe est incorrect.'],
            ]);
        }

        // Delete profile photo from storage
        if ($user->profile_image && ! str_starts_with($user->profile_image, 'http')) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Detach from agencies
        $user->agencies()->detach();

        // Delete the user
        $user->delete();
    }
}
