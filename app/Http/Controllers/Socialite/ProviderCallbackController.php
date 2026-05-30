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
        if(!in_array($provider, ['google', 'facebook'])) {
            redirect()->route('login')->withErrors(['provider' => 'Invalid provider']);
        }

        $providerUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'provider_id' => $providerUser->id,
            'provider_name' => $provider,
        ], [
            'name' => $providerUser->name,
            'email' => $providerUser->email,
            'provider_token' => $providerUser->token,
            'provider_refresh_token' => $providerUser->refreshToken,
            'profile_image' => $providerUser->getAvatar(),
        ]);

        Auth::login($user);

        return redirect('/home');
    }
}
