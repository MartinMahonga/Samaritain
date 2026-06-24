<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if (! in_array($provider, ['google', 'facebook'])) {
            redirect()->route('login')->withErrors(['provider' => 'Invalid provider']);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Throwable $th) {
            redirect()->route('login')->withErrors(['provider' => 'Something went wrong']);
        }
    }
}
