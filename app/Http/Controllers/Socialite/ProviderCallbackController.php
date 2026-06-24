<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if (! in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->withErrors(['provider' => 'Invalid provider']);
        }

        $providerUser = Socialite::driver($provider)->user();

        // Vérifier si l'utilisateur existe déjà
        $existingUser = User::where('email', $providerUser->email)->first();

        if ($existingUser) {
            // Mettre à jour le provider_id si l'utilisateur s'authentifie avec une nouvelle méthode
            if (! $existingUser->provider_id) {
                $existingUser->update([
                    'provider_id' => $providerUser->id,
                    'provider_name' => $provider,
                    'provider_token' => $providerUser->token,
                    'provider_refresh_token' => $providerUser->refreshToken,
                    'profile_image' => $providerUser->getAvatar(),
                ]);
            }
            $user = $existingUser;
        } else {
            // Créer un nouvel utilisateur
            $user = User::create([
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'provider_id' => $providerUser->id,
                'provider_name' => $provider,
                'provider_token' => $providerUser->token,
                'provider_refresh_token' => $providerUser->refreshToken,
                'profile_image' => $providerUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);

        return redirect('/properties');
    }
}
