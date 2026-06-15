<?php

namespace App\Http\Controllers;

use App\Actions\DeleteUserAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdateProfilePhotoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show(Request $request): View
    {
        return view('pages.profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateInfo(Request $request, UpdatesUserProfileInformation $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(UpdateProfilePhotoRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Delete old photo if exists
        if ($user->profile_image && ! str_starts_with($user->profile_image, 'http')) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->forceFill([
            'profile_image' => $path,
        ])->save();

        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_image && ! str_starts_with($user->profile_image, 'http')) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->forceFill([
            'profile_image' => null,
        ])->save();

        return back()->with('success', 'Photo de profil supprimée.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, UpdatesUserPasswords $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
    
    /**
     * Delete the user's account.
     */
    public function destroy(DeleteAccountRequest $request, DeleteUserAccount $deleter): RedirectResponse
    {
        $deleter->delete($request->user(), $request->validated('password'));

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé définitivement.');
    }
}
